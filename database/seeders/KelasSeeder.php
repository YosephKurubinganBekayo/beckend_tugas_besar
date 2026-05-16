<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $gurus = Guru::all();

        $classes = [
            ['nama' => 'VII-A', 'wali_idx' => 0],
            ['nama' => 'VII-B', 'wali_idx' => 1],
            ['nama' => 'VIII-A', 'wali_idx' => 2],
            ['nama' => 'VIII-B', 'wali_idx' => 3],
            ['nama' => 'IX-A', 'wali_idx' => 4],
            ['nama' => 'IX-B', 'wali_idx' => null],
        ];

        foreach ($classes as $c) {
            Kelas::query()->updateOrCreate(
                ['nama_kelas' => $c['nama']],
                ['wali_kelas_id' => $c['wali_idx'] !== null ? $gurus[$c['wali_idx']]->id : null]
            );
        }
    }
}
