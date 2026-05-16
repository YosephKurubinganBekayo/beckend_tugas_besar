<?php

namespace App\Http\Requests\Jadwal;

use Illuminate\Foundation\Http\FormRequest;

class BatchStoreJadwalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array'],
            'items.*.kelas_id' => ['required', 'integer', 'exists:kelas,id'],
            'items.*.mapel_id' => ['required', 'integer', 'exists:mata_pelajaran,id'],
            'items.*.guru_id' => ['required', 'integer', 'exists:guru,id'],
            'items.*.hari' => ['required', 'string', 'max:20'],
            'items.*.jam_mulai' => ['required', 'date_format:H:i'],
            'items.*.jam_selesai' => ['required', 'date_format:H:i'],
        ];
    }
}
