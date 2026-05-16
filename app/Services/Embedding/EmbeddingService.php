<?php

namespace App\Services\Embedding;

use App\Models\Embedding;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class EmbeddingService
{
    public function getAll(array $filters): Collection
    {
        return Embedding::query()
            ->with('siswa')
            ->when($filters['siswa_id'] ?? null, fn ($query, $siswaId) => $query->where('siswa_id', $siswaId))
            ->latest('id')
            ->limit($filters['limit'] ?? 50)
            ->get();
    }

    public function create(array $data): Embedding
    {
        return DB::transaction(fn () => Embedding::query()->create($data)->load('siswa'));
    }

    public function update(Embedding $embedding, array $data): Embedding
    {
        return DB::transaction(function () use ($embedding, $data): Embedding {
            $embedding->update($data);

            return $embedding->refresh()->load('siswa');
        });
    }

    public function delete(Embedding $embedding): void
    {
        DB::transaction(fn () => $embedding->delete());
    }
}
