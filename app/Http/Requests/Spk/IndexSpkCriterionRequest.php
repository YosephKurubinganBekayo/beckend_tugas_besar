<?php

namespace App\Http\Requests\Spk;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexSpkCriterionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'target_type' => ['sometimes', Rule::in(['guru', 'siswa'])],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
