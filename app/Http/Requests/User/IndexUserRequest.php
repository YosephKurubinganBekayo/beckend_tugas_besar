<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class IndexUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return ['search' => ['sometimes', 'string', 'max:100'], 'limit' => ['sometimes', 'integer', 'min:1', 'max:100']];
    }
}
