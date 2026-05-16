<?php

namespace App\Http\Requests\Spk;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSpkCriterionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'target_type' => ['required', Rule::in(['guru', 'siswa'])],
            'kode' => ['required', 'string', 'max:30', Rule::unique('spk_criteria', 'kode')->where('target_type', $this->input('target_type'))],
            'nama' => ['required', 'string', 'max:100'],
            'bobot' => ['required', 'numeric', 'min:0'],
            'tipe' => ['required', Rule::in(['benefit', 'cost'])],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
