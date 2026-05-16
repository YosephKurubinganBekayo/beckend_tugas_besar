<?php

namespace App\Http\Resources\Spk;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpkCriterionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'target_type' => $this->target_type,
            'kode' => $this->kode,
            'nama' => $this->nama,
            'bobot' => (float) $this->bobot,
            'tipe' => $this->tipe,
            'is_active' => $this->is_active,
        ];
    }
}
