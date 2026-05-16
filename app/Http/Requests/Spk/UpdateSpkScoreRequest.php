<?php

namespace App\Http\Requests\Spk;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSpkScoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'spk_criterion_id' => ['sometimes', 'integer', 'exists:spk_criteria,id'],
            'target_type' => ['sometimes', Rule::in(['guru', 'siswa'])],
            'target_id' => ['sometimes', 'integer', 'min:1'],
            'nilai' => ['sometimes', 'numeric', 'min:0'],
            'periode' => ['sometimes', 'nullable', 'string', 'max:30'],
        ];
    }
}
