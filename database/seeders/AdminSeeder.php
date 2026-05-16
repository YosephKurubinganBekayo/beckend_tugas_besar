<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::query()->updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nama' => 'Administrator Utama',
                'password' => Hash::make('123456'),
            ]
        );

        Admin::query()->updateOrCreate(
            ['email' => 'operator@spk.com'],
            [
                'nama' => 'Operator Sistem',
                'password' => Hash::make('password'),
            ]
        );
    }
}
