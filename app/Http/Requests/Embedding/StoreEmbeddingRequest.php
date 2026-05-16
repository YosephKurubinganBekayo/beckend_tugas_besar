<?php

namespace App\Http\Requests\Embedding;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmbeddingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'siswa_id' => ['required', 'integer', 'exists:siswa,id'],
            'embedding' => ['sometimes', 'array'],
            'embedding.*' => ['numeric'],
        ];
    }
}
