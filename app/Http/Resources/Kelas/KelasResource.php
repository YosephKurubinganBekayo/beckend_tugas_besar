<?php

namespace App\Http\Resources\Kelas;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KelasResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_kelas' => $this->nama_kelas,
            'wali_kelas_id' => $this->wali_kelas_id,
            'jumlah_siswa' => $this->whenCounted('siswa'),
            'jumlah_jadwal' => $this->whenCounted('jadwal'),
            'wali_kelas' => $this->whenLoaded('waliKelas', fn () => $this->waliKelas ? [
                'id' => $this->waliKelas->id,
                'nama' => $this->waliKelas->nama,
                'nip' => $this->waliKelas->nip,
                'email' => $this->waliKelas->email,
                'foto_url' => $this->waliKelas->foto_url,
            ] : null),
        ];
    }
}
