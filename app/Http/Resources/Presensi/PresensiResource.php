<?php

namespace App\Http\Resources\Presensi;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PresensiResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'siswa_id' => $this->siswa_id,
            'jadwal_id' => $this->jadwal_id,
            'guru_id' => $this->guru_id,
            'status' => $this->status,
            'tanggal' => $this->tanggal,
            'jam_presensi' => $this->jam_presensi,
            'siswa' => $this->whenLoaded('siswa', fn () => [
                'id' => $this->siswa->id,
                'nama' => $this->siswa->nama,
                'nis' => $this->siswa->nis,
                'jenis_kelamin' => $this->siswa->jenis_kelamin,
                'foto_url' => $this->siswa->foto_url,
            ]),
            'guru' => $this->whenLoaded('guru', fn () => [
                'id' => $this->guru->id,
                'nama' => $this->guru->nama,
                'nip' => $this->guru->nip,
                'foto_url' => $this->guru->foto_url,
            ]),
            'jadwal' => $this->whenLoaded('jadwal', fn () => [
                'id' => $this->jadwal->id,
                'hari' => $this->jadwal->hari,
                'jam_mulai' => $this->jadwal->jam_mulai,
                'jam_selesai' => $this->jadwal->jam_selesai,
                'kelas' => $this->jadwal->relationLoaded('kelas') ? [
                    'id' => $this->jadwal->kelas?->id,
                    'nama_kelas' => $this->jadwal->kelas?->nama_kelas,
                ] : null,
                'mapel' => $this->jadwal->relationLoaded('mapel') ? [
                    'id' => $this->jadwal->mapel?->id,
                    'nama_mapel' => $this->jadwal->mapel?->nama_mapel,
                ] : null,
            ]),
        ];
    }
}
