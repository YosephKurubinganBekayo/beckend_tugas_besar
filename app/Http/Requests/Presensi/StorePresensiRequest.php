<?php

namespace App\Http\Requests\Presensi;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePresensiRequest extends FormRequest
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
            'siswa_id' => [
                'required',
                'integer',
                'exists:siswa,id',
            ],
            'jadwal_id' => [
                'required',
                'integer',
                'exists:jadwal,id',
            ],
            'guru_id' => [
                'required',
                'integer',
                'exists:guru,id',
            ],
            'status' => [
                'required',
                Rule::in(['hadir', 'izin', 'sakit', 'alpha']),
            ],
            'tanggal' => [
                'required',
                'date_format:Y-m-d',
                Rule::unique('presensi', 'tanggal')
                    ->where('siswa_id', $this->integer('siswa_id'))
                    ->where('jadwal_id', $this->integer('jadwal_id')),
            ],
            'jam_presensi' => [
                'required',
                'date_format:H:i',
            ],
        ];
    }
}
