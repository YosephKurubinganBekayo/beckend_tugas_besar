<?php

namespace App\Http\Resources\Embedding;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmbeddingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'siswa_id' => $this->siswa_id,
            'embedding' => $this->embedding,
            'siswa' => $this->whenLoaded('siswa'),
        ];
    }
}
