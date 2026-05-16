<?php

namespace App\Http\Resources\Auth;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthenticatedUserResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this->resource;

        return [
            'id' => $user->id,
            'nama' => $user->nama,
            'jenis_kelamin' => $user->jenis_kelamin ?? null,
            'email' => $user->email,
            'foto_url' => $user->foto_url,
            'role' => strtolower(class_basename($user)),
            'is_wali' => $this->isWali(),
            'is_mapel' => $this->isMapel(),
        ];
    }

    private function isWali(): bool
    {
        if (! $this->resource instanceof Guru) {
            return false;
        }

        if ($this->resource->relationLoaded('kelasYangDiwalikan')) {
            return $this->resource->kelasYangDiwalikan->isNotEmpty();
        }

        return $this->resource->kelasYangDiwalikan()->exists();
    }

    private function isMapel(): bool
    {
        if (! $this->resource instanceof Guru) {
            return false;
        }

        if ($this->resource->relationLoaded('mapelYangDiajar')) {
            return $this->resource->mapelYangDiajar->isNotEmpty();
        }

        return $this->resource->mapelYangDiajar()->exists();
    }
}
