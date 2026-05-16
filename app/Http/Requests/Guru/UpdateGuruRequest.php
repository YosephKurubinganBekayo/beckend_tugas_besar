<?php

namespace App\Http\Requests\Guru;

use App\Models\Guru;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Guru|null $guru */
        $guru = $this->route('guru');

        return [
            'nama' => ['sometimes', 'string', 'max:100'],
            'jenis_kelamin' => ['sometimes', 'nullable', 'string', 'max:20'],
            'email' => ['sometimes', 'email', 'max:100', Rule::unique('guru', 'email')->ignore($guru?->id)],
            'password' => ['sometimes', 'string', 'min:6'],
            'nip' => ['sometimes', 'string', 'max:50', Rule::unique('guru', 'nip')->ignore($guru?->id)],
            'foto_url' => ['sometimes', 'nullable', 'string'],
            'mapel_ids' => [
                'sometimes',
                'array',
            ],

            'mapel_ids.*' => [
                'integer',
                'exists:mata_pelajaran,id',
            ],

            'kelas_asuh_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:kelas,id',
            ],
        ];
    }
}
