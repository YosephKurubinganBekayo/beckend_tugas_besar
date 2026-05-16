<?php

namespace App\Http\Requests\Presensi;

use App\Models\Presensi;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePresensiRequest extends FormRequest
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
        /** @var Presensi|null $presensi */
        $presensi = $this->route('presensi');

        $siswaId = $this->integer('siswa_id') ?: $presensi?->siswa_id;
        $jadwalId = $this->integer('jadwal_id') ?: $presensi?->jadwal_id;

        return [
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
            'tanggal' => [
                'sometimes',
                'date_format:Y-m-d',
                Rule::unique('presensi', 'tanggal')
                    ->where('siswa_id', $siswaId)
                    ->where('jadwal_id', $jadwalId)
                    ->ignore($presensi?->id),
            ],
            'jam_presensi' => [
                'sometimes',
                'date_format:H:i',
            ],
        ];
    }
}
