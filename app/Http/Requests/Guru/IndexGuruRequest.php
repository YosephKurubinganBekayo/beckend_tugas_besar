<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;

class IndexGuruRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
{
    return [
        'search' => ['nullable', 'string', 'max:100'],
        'limit' => ['nullable', 'integer', 'min:1', 'max:100'],
    ];
}
}
