<?php

namespace App\Http\Requests\Embedding;

use Illuminate\Foundation\Http\FormRequest;

class IndexEmbeddingRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return ['siswa_id' => ['sometimes', 'integer', 'exists:siswa,id'], 'limit' => ['sometimes', 'integer', 'min:1', 'max:100']];
    }
}
