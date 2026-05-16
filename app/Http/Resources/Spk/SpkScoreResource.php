<?php

namespace App\Http\Resources\Spk;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpkScoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'spk_criterion_id' => $this->spk_criterion_id,
            'target_type' => $this->target_type,
            'target_id' => $this->target_id,
            'nilai' => (float) $this->nilai,
            'periode' => $this->periode,
            'criterion' => $this->whenLoaded('criterion'),
        ];
    }
}
