<?php

namespace App\Services\MataPelajaran;

use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MataPelajaranService
{
    public function getAll(array $filters): Collection
    {
        return MataPelajaran::query()
            ->with('guru')
            ->withCount(['guru', 'jadwal'])
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('nama_mapel', 'like', "%{$search}%"))
            ->orderBy('nama_mapel')
            ->limit($filters['limit'] ?? 50)
            ->get();
    }

    public function create(array $data): MataPelajaran
    {
        return DB::transaction(fn () => MataPelajaran::query()->create($data)->load('guru')->loadCount(['guru', 'jadwal']));
    }

    public function update(MataPelajaran $mataPelajaran, array $data): MataPelajaran
    {
        return DB::transaction(function () use ($mataPelajaran, $data): MataPelajaran {
            $mataPelajaran->update($data);

            return $mataPelajaran->refresh()->load('guru')->loadCount(['guru', 'jadwal']);
        });
    }

    public function delete(MataPelajaran $mataPelajaran): void
    {
        DB::transaction(fn () => $mataPelajaran->delete());
    }
}
