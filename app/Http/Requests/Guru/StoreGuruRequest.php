<?php

namespace App\Http\Requests\Guru;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'nama' => [
                'required',
                'string',
                'max:100'
            ],

            'jenis_kelamin' => [
                'nullable',
                'string',
                'max:20'
            ],

            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('guru', 'email')
            ],

            'password' => [
                'required',
                'string',
                'min:6'
            ],

            'nip' => [
                'required',
                'string',
                'max:50',
                Rule::unique('guru', 'nip')
            ],

            'foto_url' => [
                'nullable',
                'string'
            ],

            'mapel_ids' => [
                'nullable',
                'array'
            ],

            'mapel_ids.*' => [
                'integer',
                'exists:mata_pelajaran,id'
            ],

            'kelas_asuh_id' => [
                'nullable',
                'integer',
                'exists:kelas,id'
            ],
        ];
    }
}
