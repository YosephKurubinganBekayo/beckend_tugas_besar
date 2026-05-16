<?php

namespace App\Services\Auth;

use App\Models\Admin;
use App\Models\Guru;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthService
{
    /**
     * @param  array{email: string, password: string}  $credentials
     * @return array{access_token: string, token_type: string, user: Authenticatable}
     */
    public function login(array $credentials): array
    {
        $user = $this->findUserByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->getAuthPassword())) {
            throw new HttpException(401, 'Login gagal');
        }

        return DB::transaction(function () use ($user): array {
            $user->tokens()->delete();

            return $this->tokenResponse($user);
        });
    }

    /**
     * @param  array{nama: string, email: string, password: string}  $data
     * @return array{access_token: string, token_type: string, user: Admin}
     */
    public function registerAdmin(array $data): array
    {
        return DB::transaction(function () use ($data): array {
            $admin = Admin::query()->create([
                'nama' => $data['nama'],
                'email' => $data['email'],
                'password' => $data['password'],
                'foto_url' => null,
            ]);

            return $this->tokenResponse($admin);
        });
    }

    public function logout(Authenticatable $user): void
    {
        $user->currentAccessToken()?->delete();
    }

    private function findUserByEmail(string $email): Admin|Guru|null
    {
        $guru = Guru::query()
            ->with([
                'kelasYangDiwalikan',
                'mapelYangDiajar',
            ])
            ->where('email', $email)
            ->first();

        if ($guru) {
            return $guru;
        }

        return Admin::query()
            ->where('email', $email)
            ->first();
    }

    /**
     * @return array{access_token: string, token_type: string, user: Authenticatable}
     */
    private function tokenResponse(Authenticatable $user): array
    {
        return [
            'access_token' => $user->createToken('auth_token')->plainTextToken,
            'token_type' => 'bearer',
            'user' => $user,
        ];
    }
}
