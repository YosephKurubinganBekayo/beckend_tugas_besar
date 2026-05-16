<?php

namespace App\Http\Requests\Spk;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexSpkScoreRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'target_type' => ['sometimes', Rule::in(['guru', 'siswa'])],
            'target_id' => ['sometimes', 'integer', 'min:1'],
            'periode' => ['sometimes', 'nullable', 'string', 'max:30'],
        ];
    }
}
