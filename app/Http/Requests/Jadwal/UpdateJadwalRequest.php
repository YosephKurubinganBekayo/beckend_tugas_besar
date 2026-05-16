<?php

namespace App\Http\Requests\Jadwal;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJadwalRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'kelas_id' => ['sometimes', 'integer', 'exists:kelas,id'],
            'mapel_id' => ['sometimes', 'integer', 'exists:mata_pelajaran,id'],
            'guru_id' => ['sometimes', 'integer', 'exists:guru,id'],
            'hari' => ['sometimes', 'string', 'max:20'],
            'jam_mulai' => ['sometimes', 'date_format:H:i'],
            'jam_selesai' => ['sometimes', 'date_format:H:i'],
        ];
    }
}
