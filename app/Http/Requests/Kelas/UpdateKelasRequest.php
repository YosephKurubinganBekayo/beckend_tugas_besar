<?php

namespace App\Http\Requests\Kelas;

use App\Models\Kelas;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        /** @var Kelas|null $kelas */
        $kelas = $this->route('kelas');

        return [
            'nama_kelas' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('kelas', 'nama_kelas')->ignore($kelas?->id),
            ],
            'wali_kelas_id' => [
                'sometimes',
                'nullable',
                'integer',
                'exists:guru,id',
                Rule::unique('kelas', 'wali_kelas_id')->ignore($kelas?->id),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_kelas' => is_string($this->nama_kelas) ? trim($this->nama_kelas) : $this->nama_kelas,
        ]);
    }
}
