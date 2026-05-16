<?php

namespace App\Http\Requests\Spk;

use App\Models\SpkCriterion;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSpkCriterionRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        /** @var SpkCriterion|null $criterion */
        $criterion = $this->route('criterion');
        $targetType = $this->input('target_type', $criterion?->target_type);

        return [
            'target_type' => ['sometimes', Rule::in(['guru', 'siswa'])],
            'kode' => ['sometimes', 'string', 'max:30', Rule::unique('spk_criteria', 'kode')->where('target_type', $targetType)->ignore($criterion?->id)],
            'nama' => ['sometimes', 'string', 'max:100'],
            'bobot' => ['sometimes', 'numeric', 'min:0'],
            'tipe' => ['sometimes', Rule::in(['benefit', 'cost'])],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
