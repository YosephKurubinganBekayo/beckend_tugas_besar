<?php

namespace App\Http\Requests\GuruMapel;

use App\Models\GuruMapel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGuruMapelRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        /** @var GuruMapel|null $guruMapel */
        $guruMapel = $this->route('guruMapel');
        $guruId = $this->integer('guru_id') ?: $guruMapel?->guru_id;

        return [
            'guru_id' => ['sometimes', 'integer', 'exists:guru,id'],
            'mapel_id' => ['sometimes', 'integer', 'exists:mata_pelajaran,id', Rule::unique('guru_mapel', 'mapel_id')->where('guru_id', $guruId)->ignore($guruMapel?->id)],
        ];
    }
}
