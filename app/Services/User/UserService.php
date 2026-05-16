<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class UserService
{
    public function getAll(array $filters): Collection
    {
        return User::query()
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
            ->latest('id')
            ->limit($filters['limit'] ?? 50)
            ->get();
    }

    public function create(array $data): User
    {
        return DB::transaction(fn () => User::query()->create($data));
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data): User {
            $user->update($data);

            return $user->refresh();
        });
    }

    public function delete(User $user): void
    {
        DB::transaction(fn () => $user->delete());
    }
}
