<?php

namespace App\Services\Spk;

use App\Models\Guru;
use App\Models\Siswa;
use App\Models\SpkCriterion;
use App\Models\SpkScore;
use App\Services\Spk\Calculators\RankingCalculator;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SpkRankingService
{
    public function __construct(
        private readonly RankingCalculator $calculator
    ) {}

    public function rank(string $targetType, ?string $periode = null): Collection
    {
        $criteria = SpkCriterion::query()
            ->where('target_type', $targetType)
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        if ($criteria->isEmpty()) {
            throw new HttpException(422, 'Kriteria SPK belum tersedia');
        }

        $alternatives = $this->alternatives($targetType, $criteria, $periode);

        if ($alternatives->isEmpty()) {
            throw new HttpException(422, 'Nilai alternatif SPK belum tersedia');
        }

        return $this->calculator->calculate(
            $alternatives,
            $criteria->map(fn (SpkCriterion $criterion): array => [
                'id' => $criterion->id,
                'kode' => $criterion->kode,
                'nama' => $criterion->nama,
                'bobot' => (float) $criterion->bobot,
                'tipe' => $criterion->tipe,
            ])
        );
    }

    private function alternatives(string $targetType, Collection $criteria, ?string $periode): Collection
    {
        $targets = $targetType === 'guru'
            ? Guru::query()->select(['id', 'nama'])->orderBy('nama')->get()
            : Siswa::query()->select(['id', 'nama'])->orderBy('nama')->get();

        $scores = SpkScore::query()
            ->where('target_type', $targetType)
            ->whereIn('spk_criterion_id', $criteria->pluck('id'))
            ->when($periode !== null, fn ($query) => $query->where('periode', $periode))
            ->get()
            ->groupBy('target_id');

        return $targets->map(function ($target) use ($scores): array {
            return [
                'id' => $target->id,
                'nama' => $target->nama,
                'scores' => $scores
                    ->get($target->id, collect())
                    ->mapWithKeys(fn (SpkScore $score): array => [
                        $score->spk_criterion_id => (float) $score->nilai,
                    ])
                    ->all(),
            ];
        })->filter(fn (array $alternative): bool => $alternative['scores'] !== [])->values();
    }
}
