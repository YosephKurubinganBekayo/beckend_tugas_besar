<?php

namespace App\Http\Resources\GuruMapel;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuruMapelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'guru_id' => $this->guru_id,
            'mapel_id' => $this->mapel_id,
            'guru' => $this->whenLoaded('guru'),
            'mapel' => $this->whenLoaded('mapel'),
        ];
    }
}
