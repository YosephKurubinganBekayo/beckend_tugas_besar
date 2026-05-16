<?php

namespace App\Services\Kelas;

use App\Models\Kelas;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class KelasService
{
    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Kelas>
     */
    public function getAll(array $filters): Collection
    {
        return Kelas::query()
            ->with('waliKelas')
            ->withCount([
                'siswa',
                'jadwal',
            ])
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('nama_kelas', 'like', "%{$search}%"))
            ->when($filters['wali_kelas_id'] ?? null, fn ($query, $waliKelasId) => $query->where('wali_kelas_id', $waliKelasId))
            ->orderBy('nama_kelas')
            ->limit($filters['limit'] ?? 50)
            ->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Kelas
    {
        return DB::transaction(function () use ($data): Kelas {
            return Kelas::query()
                ->create($data)
                ->load('waliKelas')
                ->loadCount([
                    'siswa',
                    'jadwal',
                ]);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Kelas $kelas, array $data): Kelas
    {
        return DB::transaction(function () use ($kelas, $data): Kelas {
            $kelas->update($data);

            return $kelas->refresh()
                ->load('waliKelas')
                ->loadCount([
                    'siswa',
                    'jadwal',
                ]);
        });
    }

    public function delete(Kelas $kelas): void
    {
        DB::transaction(fn () => $kelas->delete());
    }
}
