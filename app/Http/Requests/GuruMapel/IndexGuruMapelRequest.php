<?php

namespace App\Http\Requests\GuruMapel;

use Illuminate\Foundation\Http\FormRequest;

class IndexGuruMapelRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'guru_id' => ['sometimes', 'integer', 'exists:guru,id'],
            'mapel_id' => ['sometimes', 'integer', 'exists:mata_pelajaran,id'],
            'limit' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
