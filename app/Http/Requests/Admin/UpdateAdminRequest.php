<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        /** @var Admin|null $admin */
        $admin = $this->route('admin');

        return [
            'nama' => ['sometimes', 'string', 'max:100'],
            'email' => ['sometimes', 'email', 'max:100', Rule::unique('admin', 'email')->ignore($admin?->id)],
            'password' => ['sometimes', 'string', 'min:6'],
            'foto_url' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
