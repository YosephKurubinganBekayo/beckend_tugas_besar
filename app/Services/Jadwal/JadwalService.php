<?php

namespace App\Services\Jadwal;

use App\Models\Jadwal;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class JadwalService
{
    public function getAll(array $filters): Collection
    {
        return Jadwal::query()
            ->with(['kelas', 'mapel', 'guru'])
            ->when($filters['kelas_id'] ?? null, fn ($query, $kelasId) => $query->where('kelas_id', $kelasId))
            ->when($filters['mapel_id'] ?? null, fn ($query, $mapelId) => $query->where('mapel_id', $mapelId))
            ->when($filters['guru_id'] ?? null, fn ($query, $guruId) => $query->where('guru_id', $guruId))
            ->when($filters['hari'] ?? null, fn ($query, $hari) => $query->where('hari', $hari))
            ->latest('id')
            ->limit($filters['limit'] ?? 50)
            ->get();
    }

    public function create(array $data): Jadwal
    {
        return DB::transaction(fn () => Jadwal::query()->create($data)->load(['kelas', 'mapel', 'guru']));
    }

    public function update(Jadwal $jadwal, array $data): Jadwal
    {
        return DB::transaction(function () use ($jadwal, $data): Jadwal {
            $jadwal->update($data);

            return $jadwal->refresh()->load(['kelas', 'mapel', 'guru']);
        });
    }

    public function batchCreate(array $items): Collection
    {
        return DB::transaction(function () use ($items) {
            $created = new Collection();
            foreach ($items as $item) {
                $created->push(Jadwal::query()->create($item));
            }
            return $created->load(['kelas', 'mapel', 'guru']);
        });
    }

    public function delete(Jadwal $jadwal): void
    {
        DB::transaction(fn () => $jadwal->delete());
    }
}
