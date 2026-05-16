<?php

namespace App\Http\Requests\GuruMapel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuruMapelRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'guru_id' => ['required', 'integer', 'exists:guru,id'],
            'mapel_id' => ['required', 'integer', 'exists:mata_pelajaran,id', Rule::unique('guru_mapel', 'mapel_id')->where('guru_id', $this->integer('guru_id'))],
        ];
    }
}
