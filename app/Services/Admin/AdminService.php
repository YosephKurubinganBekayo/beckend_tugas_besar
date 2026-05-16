<?php

namespace App\Services\Admin;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class AdminService
{
    public function getAll(array $filters): Collection
    {
        return Admin::query()
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('nama', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
            ->latest('id')
            ->limit($filters['limit'] ?? 50)
            ->get();
    }

    public function create(array $data): Admin
    {
        return DB::transaction(fn () => Admin::query()->create($data));
    }

    public function update(Admin $admin, array $data): Admin
    {
        return DB::transaction(function () use ($admin, $data): Admin {
            $admin->update($data);

            return $admin->refresh();
        });
    }

    public function delete(Admin $admin): void
    {
        DB::transaction(fn () => $admin->delete());
    }
}
