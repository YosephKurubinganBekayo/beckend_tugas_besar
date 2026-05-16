<?php

namespace App\Http\Requests\Siswa;

use App\Models\Siswa;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiswaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        /** @var Siswa|null $siswa */
        $siswa = $this->route('siswa');

        return [
            'nama' => ['sometimes', 'string', 'max:100'],
            'nis' => ['sometimes', 'string', 'max:50', Rule::unique('siswa', 'nis')->ignore($siswa?->id)],
            'jenis_kelamin' => ['sometimes', 'nullable', 'string', 'max:20'],
            'kelas_id' => ['sometimes', 'integer', 'exists:kelas,id'],
            'alamat' => ['sometimes', 'nullable', 'string', 'max:255'],
            'foto_url' => ['sometimes', 'nullable', 'string', 'max:255'],
            'embedding_status' => ['sometimes', 'string', 'max:30'],
        ];
    }
}
