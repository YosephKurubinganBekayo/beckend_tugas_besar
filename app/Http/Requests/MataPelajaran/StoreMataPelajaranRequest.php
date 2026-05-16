<?php

namespace App\Http\Requests\MataPelajaran;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMataPelajaranRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return ['nama_mapel' => ['required', 'string', 'max:100', Rule::unique('mata_pelajaran', 'nama_mapel')]];
    }
}
