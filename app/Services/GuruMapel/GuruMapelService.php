<?php

namespace App\Services\GuruMapel;

use App\Models\GuruMapel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class GuruMapelService
{
    public function getAll(array $filters): Collection
    {
        return GuruMapel::query()
            ->with(['guru', 'mapel'])
            ->when($filters['guru_id'] ?? null, fn ($query, $guruId) => $query->where('guru_id', $guruId))
            ->when($filters['mapel_id'] ?? null, fn ($query, $mapelId) => $query->where('mapel_id', $mapelId))
            ->latest('id')
            ->limit($filters['limit'] ?? 50)
            ->get();
    }

    public function create(array $data): GuruMapel
    {
        return DB::transaction(fn () => GuruMapel::query()->create($data)->load(['guru', 'mapel']));
    }

    public function update(GuruMapel $guruMapel, array $data): GuruMapel
    {
        return DB::transaction(function () use ($guruMapel, $data): GuruMapel {
            $guruMapel->update($data);

            return $guruMapel->refresh()->load(['guru', 'mapel']);
        });
    }

    public function delete(GuruMapel $guruMapel): void
    {
        DB::transaction(fn () => $guruMapel->delete());
    }
}
