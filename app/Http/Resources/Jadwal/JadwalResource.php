<?php

namespace App\Http\Resources\Jadwal;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kelas_id' => $this->kelas_id,
            'mapel_id' => $this->mapel_id,
            'guru_id' => $this->guru_id,
            'hari' => $this->hari,
            'jam_mulai' => $this->jam_mulai,
            'jam_selesai' => $this->jam_selesai,
            'kelas' => $this->whenLoaded('kelas'),
            'mapel' => $this->whenLoaded('mapel'),
            'guru' => $this->whenLoaded('guru'),
        ];
    }
}
