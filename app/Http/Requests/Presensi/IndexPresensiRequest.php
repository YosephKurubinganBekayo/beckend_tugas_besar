<?php

namespace App\Http\Requests\Presensi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexPresensiRequest extends FormRequest
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
            'tanggal' => [
                'sometimes',
                'date_format:Y-m-d',
            ],
            'siswa_id' => [
                'sometimes',
                'integer',
                'exists:siswa,id',
            ],
            'jadwal_id' => [
                'sometimes',
                'integer',
                'exists:jadwal,id',
            ],
            'guru_id' => [
                'sometimes',
                'integer',
                'exists:guru,id',
            ],
            'status' => [
                'sometimes',
                Rule::in(['hadir', 'izin', 'sakit', 'alpha']),
            ],
            'limit' => [
                'sometimes',
                'integer',
                'min:1',
                'max:100',
            ],
        ];
    }
}
