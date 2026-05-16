<?php

namespace App\Http\Requests\Kelas;

use Illuminate\Foundation\Http\FormRequest;

class IndexKelasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'search' => [
                'nullable',
                'string',
                'max:100',
            ],
            'wali_kelas_id' => [
                'nullable',
                'integer',
                'exists:guru,id',
            ],
            'limit' => [
                'nullable',
                'integer',
                'min:1',
                'max:100',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('search')) {
            $this->merge([
                'search' => is_string($this->search)
                    ? trim($this->search)
                    : $this->search,
            ]);
        }
    }

    // protected function prepareForValidation(): void
    // {
    //     $this->merge([
    //         'search' => is_string($this->search) ? trim($this->search) : $this->search,
    //     ]);
    // }
}
