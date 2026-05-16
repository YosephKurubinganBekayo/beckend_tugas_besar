<?php

namespace App\Http\Resources\MataPelajaran;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MataPelajaranResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_mapel' => $this->nama_mapel,
            'jumlah_guru' => $this->whenCounted('guru'),
            'jumlah_jadwal' => $this->whenCounted('jadwal'),
            'guru' => $this->whenLoaded('guru'),
        ];
    }
}
