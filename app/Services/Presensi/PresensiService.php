<?php

namespace App\Services\Presensi;

use App\Models\Presensi;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PresensiService
{
    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, Presensi>
     */
    public function getAll(array $filters): Collection
    {
        return Presensi::query()
            ->with([
                'siswa',
                'guru',
                'jadwal.kelas',
                'jadwal.mapel',
            ])
            ->when($filters['tanggal'] ?? null, fn ($query, $tanggal) => $query->where('tanggal', $tanggal))
            ->when($filters['siswa_id'] ?? null, fn ($query, $siswaId) => $query->where('siswa_id', $siswaId))
            ->when($filters['jadwal_id'] ?? null, fn ($query, $jadwalId) => $query->where('jadwal_id', $jadwalId))
            ->when($filters['guru_id'] ?? null, fn ($query, $guruId) => $query->where('guru_id', $guruId))
            ->when($filters['status'] ?? null, fn ($query, $status) => $query->where('status', $status))
            ->latest('id')
            ->limit($filters['limit'] ?? 50)
            ->get();
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Presensi
    {
        return DB::transaction(function () use ($data): Presensi {
            return Presensi::query()
                ->create($data)
                ->load([
                    'siswa',
                    'guru',
                    'jadwal.kelas',
                    'jadwal.mapel',
                ]);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Presensi $presensi, array $data): Presensi
    {
        return DB::transaction(function () use ($presensi, $data): Presensi {
            $presensi->update($data);

            return $presensi->refresh()
                ->load([
                    'siswa',
                    'guru',
                    'jadwal.kelas',
                    'jadwal.mapel',
                ]);
        });
    }

    public function delete(Presensi $presensi): void
    {
        DB::transaction(fn () => $presensi->delete());
    }
}
