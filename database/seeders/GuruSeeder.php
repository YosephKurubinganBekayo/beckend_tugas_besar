<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
{
    public function run(): void
    {
        $mapelNames = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'IPA',
            'IPS',
            'PJOK',
            'Seni Budaya',
            'Pendidikan Agama',
            'Informatika',
            'PKn',
        ];

        foreach ($mapelNames as $name) {
            MataPelajaran::query()->updateOrCreate(['nama_mapel' => $name], ['nama_mapel' => $name]);
        }

        $mapels = MataPelajaran::all();

        $gurus = [
            [
                'nama' => 'guru.',
                'email' => 'guru@gmail.com',
                'nip' => '123456',
                'jenis_kelamin' => 'Laki-laki',
                'mapel_indices' => [0, 1], // Matematika, B.Indo
            ],
            
        ];

        foreach ($gurus as $g) {
            $guru = Guru::query()->updateOrCreate(
                ['email' => $g['email']],
                [
                    'nama' => $g['nama'],
                    'password' => Hash::make('password'),
                    'nip' => $g['nip'],
                    'jenis_kelamin' => $g['jenis_kelamin'],
                ]
            );

            $mapelIds = [];
            foreach ($g['mapel_indices'] as $idx) {
                if (isset($mapels[$idx])) {
                    $mapelIds[] = $mapels[$idx]->id;
                }
            }
            $guru->mataPelajaran()->sync($mapelIds);
        }
    }
}
