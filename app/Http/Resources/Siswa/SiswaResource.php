<?php

namespace App\Http\Resources\Siswa;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'nis' => $this->nis,
            'jenis_kelamin' => $this->jenis_kelamin,
            'kelas_id' => $this->kelas_id,
            'alamat' => $this->alamat,
            'foto_url' => $this->foto_url,
            'embedding_status' => $this->embedding_status,
            'jumlah_embedding' => $this->whenCounted('embeddings'),
            'kelas' => $this->whenLoaded('kelas', fn () => $this->kelas ? [
                'id' => $this->kelas->id,
                'nama_kelas' => $this->kelas->nama_kelas,
            ] : null),
        ];
    }
}
