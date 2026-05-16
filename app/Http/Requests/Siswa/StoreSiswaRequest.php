<?php

namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSiswaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'max:100'],
            'nis' => ['required', 'string', 'max:50', Rule::unique('siswa', 'nis')],
            'jenis_kelamin' => ['nullable', 'string', 'max:20'],
            'kelas_id' => ['required', 'integer', 'exists:kelas,id'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'foto_url' => ['nullable', 'string', 'max:255'],
            'embedding_status' => ['sometimes', 'string', 'max:30'],
        ];
    }
}
