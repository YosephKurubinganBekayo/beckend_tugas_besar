<?php

namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;

class IndexSiswaRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'string', 'max:100'],
            'kelas_id' => ['sometimes', 'integer', 'exists:kelas,id'],
            'embedding_status' => ['sometimes', 'string', 'max:30'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
