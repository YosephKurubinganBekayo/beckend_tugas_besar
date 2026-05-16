<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:admin,email',
                'unique:guru,email',
            ],
            'password' => [
                'required',
                'string',
                'min:6',
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama' => is_string($this->nama) ? trim($this->nama) : $this->nama,
            'email' => is_string($this->email) ? trim($this->email) : $this->email,
            'password' => is_string($this->password) ? trim($this->password) : $this->password,
        ]);
    }
}
