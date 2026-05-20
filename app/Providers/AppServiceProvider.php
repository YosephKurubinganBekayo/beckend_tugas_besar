<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Embedding;
use App\Models\Guru;
use App\Models\GuruMapel;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Presensi;
use App\Models\Siswa;
use App\Models\SpkCriterion;
use App\Models\SpkScore;
use App\Models\User;
use App\Observers\BroadcastModelChanges;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        $realtimeModels = [
            Admin::class,
            Embedding::class,
            Guru::class,
            GuruMapel::class,
            Jadwal::class,
            Kelas::class,
            MataPelajaran::class,
            Presensi::class,
            Siswa::class,
            SpkCriterion::class,
            SpkScore::class,
            User::class,
        ];

        foreach ($realtimeModels as $model) {
            $model::observe(BroadcastModelChanges::class);
        }
    }
}
