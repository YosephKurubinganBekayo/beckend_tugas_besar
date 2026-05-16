<?php

namespace App\Http\Requests\MataPelajaran;

use App\Models\MataPelajaran;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMataPelajaranRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        /** @var MataPelajaran|null $mataPelajaran */
        $mataPelajaran = $this->route('mataPelajaran');

        return ['nama_mapel' => ['sometimes', 'string', 'max:100', Rule::unique('mata_pelajaran', 'nama_mapel')->ignore($mataPelajaran?->id)]];
    }
}
