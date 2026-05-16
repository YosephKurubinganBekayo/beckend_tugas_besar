<?php

namespace App\Http\Requests\Spk;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSpkScoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'spk_criterion_id' => ['required', 'integer', 'exists:spk_criteria,id'],
            'target_type' => ['required', Rule::in(['guru', 'siswa'])],
            'target_id' => ['required', 'integer', 'min:1'],
            'nilai' => ['required', 'numeric', 'min:0'],
            'periode' => ['nullable', 'string', 'max:30'],
        ];
    }
}
