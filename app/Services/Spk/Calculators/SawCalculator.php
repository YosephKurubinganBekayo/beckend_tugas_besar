<?php

namespace App\Services\Spk\Calculators;

use Illuminate\Support\Collection;

class SawCalculator implements RankingCalculator
{
    public function calculate(Collection $alternatives, Collection $criteria): Collection
    {
        $dividers = $this->dividers($alternatives, $criteria);

        $rankings = $alternatives->map(function (array $alternative) use ($criteria, $dividers): array {
            $details = [];
            $total = 0.0;

            foreach ($criteria as $criterion) {
                $criterionId = $criterion['id'];
                $rawValue = (float) ($alternative['scores'][$criterionId] ?? 0);
                $divider = $dividers[$criterionId] ?? 0.0;
                $normalized = $this->normalize($rawValue, $divider, $criterion['tipe']);
                $weighted = $normalized * (float) $criterion['bobot'];
                $total += $weighted;

                $details[] = [
                    'criterion_id' => $criterionId,
                    'nilai' => $rawValue,
                    'normalisasi' => round($normalized, 6),
                    'bobot' => (float) $criterion['bobot'],
                    'nilai_terbobot' => round($weighted, 6),
                ];
            }

            return [
                'id' => $alternative['id'],
                'nama' => $alternative['nama'],
                'total' => round($total, 6),
                'detail' => $details,
            ];
        })->sortByDesc('total')->values();

        return $rankings->map(function (array $ranking, int $index): array {
            $ranking['peringkat'] = $index + 1;

            return $ranking;
        });
    }

    private function dividers(Collection $alternatives, Collection $criteria): array
    {
        return $criteria->mapWithKeys(function (array $criterion): array {
            return [$criterion['id'] => 0.0];
        })->map(function (float $unused, int $criterionId) use ($alternatives, $criteria): float {
            $criterion = $criteria->firstWhere('id', $criterionId);
            $values = $alternatives
                ->map(fn (array $alternative): float => (float) ($alternative['scores'][$criterionId] ?? 0))
                ->filter(fn (float $value): bool => $value > 0);

            if ($values->isEmpty()) {
                return 0.0;
            }

            return ($criterion['tipe'] ?? 'benefit') === 'cost'
                ? (float) $values->min()
                : (float) $values->max();
        })->all();
    }

    private function normalize(float $value, float $divider, string $type): float
    {
        if ($value <= 0 || $divider <= 0) {
            return 0.0;
        }

        return $type === 'cost'
            ? $divider / $value
            : $value / $divider;
    }
}
