<?php

namespace App\Services\Guru;

use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Services\GuruMapel\GuruMapelService;
use App\Models\GuruMapel;
class GuruService
{
    public function __construct(
        private readonly GuruMapelService $guruMapelService
    ) {}
    public function getAll(array $filters): Collection
    {
        return Guru::query()
            ->with(['kelasYangDiwalikan', 'mataPelajaran'])
            ->withCount(['kelasYangDiwalikan', 'mapelYangDiajar'])
            ->when($filters['search'] ?? null, fn($query, $search) => $query
                ->where('nama', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('nip', 'like', "%{$search}%"))
            ->latest('id')
            ->limit($filters['limit'] ?? 50)
            ->get();
    }

    public function create(array $data): Guru
    {
        return DB::transaction(function () use ($data) {

            $guru = Guru::query()->create($data);

            /*
        |--------------------------------------------------------------------------
        | INSERT MAPEL
        |--------------------------------------------------------------------------
        */

            foreach ($data['mapel_ids'] ?? [] as $mapelId) {

                $this->guruMapelService->create([
                    'guru_id' => $guru->id,
                    'mapel_id' => $mapelId,
                ]);
            }

            /*
        |--------------------------------------------------------------------------
        | SET WALI KELAS
        |--------------------------------------------------------------------------
        */

            if (!empty($data['kelas_asuh_id'])) {

                Kelas::query()
                    ->where('id', $data['kelas_asuh_id'])
                    ->update([
                        'wali_kelas_id' => $guru->id
                    ]);
            }

            return $guru->load([
                'kelasYangDiwalikan',
                'mataPelajaran'
            ])->loadCount([
                'kelasYangDiwalikan',
                'mapelYangDiajar'
            ]);
        });
    }

    public function update(Guru $guru, array $data): Guru
    {
        return DB::transaction(function () use ($guru, $data): Guru {

            $guru->update($data);

            /*
        |--------------------------------------------------------------------------
        | UPDATE MAPEL
        |--------------------------------------------------------------------------
        */

            if (isset($data['mapel_ids'])) {

                GuruMapel::query()
                    ->where('guru_id', $guru->id)
                    ->delete();

                foreach ($data['mapel_ids'] as $mapelId) {

                    $this->guruMapelService->create([
                        'guru_id' => $guru->id,
                        'mapel_id' => $mapelId,
                    ]);
                }
            }

            /*
        |--------------------------------------------------------------------------
        | RESET WALI LAMA
        |--------------------------------------------------------------------------
        */

            Kelas::query()
                ->where('wali_kelas_id', $guru->id)
                ->update([
                    'wali_kelas_id' => null
                ]);

            /*
        |--------------------------------------------------------------------------
        | SET WALI BARU
        |--------------------------------------------------------------------------
        */

            if (!empty($data['kelas_asuh_id'])) {

                Kelas::query()
                    ->where('id', $data['kelas_asuh_id'])
                    ->update([
                        'wali_kelas_id' => $guru->id
                    ]);
            }

            return $guru->refresh()
                ->load([
                    'kelasYangDiwalikan',
                    'mataPelajaran'
                ])
                ->loadCount([
                    'kelasYangDiwalikan',
                    'mapelYangDiajar'
                ]);
        });
    }

    public function delete(Guru $guru): void
    {
        DB::transaction(function () use ($guru) {


            Kelas::query()
                ->where('wali_kelas_id', $guru->id)
                ->update([
                    'wali_kelas_id' => null
                ]);

            $guru->delete();
        });
    }
}
