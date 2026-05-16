<?php

namespace App\Services\Siswa;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SiswaService
{
    public function getAll(array $filters): Collection
    {
        return Siswa::query()
            ->with('kelas')
            ->withCount('embeddings')
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('nama', 'like', "%{$search}%")->orWhere('nis', 'like', "%{$search}%"))
            ->when($filters['kelas_id'] ?? null, fn ($query, $kelasId) => $query->where('kelas_id', $kelasId))
            ->when($filters['embedding_status'] ?? null, fn ($query, $status) => $query->where('embedding_status', $status))
            ->latest('id')
            ->limit($filters['limit'] ?? 50)
            ->get();
    }

    public function create(array $data): Siswa
    {
        return DB::transaction(fn () => Siswa::query()->create($data)->load('kelas')->loadCount('embeddings'));
    }

    public function update(Siswa $siswa, array $data): Siswa
    {
        return DB::transaction(function () use ($siswa, $data): Siswa {
            $siswa->update($data);

            return $siswa->refresh()->load('kelas')->loadCount('embeddings');
        });
    }

    public function delete(Siswa $siswa): void
    {
        DB::transaction(fn () => $siswa->delete());
    }
}
