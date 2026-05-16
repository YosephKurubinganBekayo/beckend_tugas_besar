<?php

namespace App\Http\Requests\Kelas;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreKelasRequest extends FormRequest
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
        return [
            'nama_kelas' => [
                'required',
                'string',
                'max:50',
                Rule::unique('kelas', 'nama_kelas'),
            ],
            'wali_kelas_id' => [
                'nullable',
                'integer',
                'exists:guru,id',
                Rule::unique('kelas', 'wali_kelas_id'),
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
