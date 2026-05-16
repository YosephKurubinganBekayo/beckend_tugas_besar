<?php

namespace App\Http\Resources\Guru;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuruResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'nama' => $this->nama,

            'jenis_kelamin' => $this->jenis_kelamin,

            'email' => $this->email,

            'nip' => $this->nip,

            'foto_url' => $this->foto_url,

            /*
            |--------------------------------------------------------------------------
            | APPENDED IDS
            |--------------------------------------------------------------------------
            */

            'mapel_id' => $this->mapel_id,

            'mapel_ids' => $this->mapel_ids,

            'kelas_asuh_id' => $this->kelas_asuh_id,

            'kelas_asuh_ids' => $this->kelas_asuh_ids,

            /*
            |--------------------------------------------------------------------------
            | COUNTS
            |--------------------------------------------------------------------------
            */

            'is_wali' => $this->whenCounted(
                'kelasYangDiwalikan',
                fn () => $this->kelas_yang_diwalikan_count > 0
            ),

            'jumlah_mapel' => $this->whenCounted(
                'mapelYangDiajar'
            ),

            /*
            |--------------------------------------------------------------------------
            | RELATIONS
            |--------------------------------------------------------------------------
            */

            'kelas_yang_diwalikan' => $this->whenLoaded(
                'kelasYangDiwalikan'
            ),

            'mapel_yang_diajar' => $this->whenLoaded(
                'mataPelajaran',
                fn () => $this->mataPelajaran->map(fn ($mapel) => [
                    'id' => $mapel->id,
                    'nama_mapel' => $mapel->nama_mapel,
                ])
            ),
        ];
    }
}