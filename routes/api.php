<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmbeddingController;
use App\Http\Controllers\Api\GuruController;
use App\Http\Controllers\Api\GuruMapelController;
use App\Http\Controllers\Api\JadwalController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\MataPelajaranController;
use App\Http\Controllers\Api\PresensiController;
use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\Spk\SpkCriterionController;
use App\Http\Controllers\Api\Spk\SpkRankingController;
use App\Http\Controllers\Api\Spk\SpkScoreController;
use App\Http\Controllers\Api\UserController;

Route::post('/register', [
    AuthController::class,
    'register'
])->middleware('throttle:10,1');

Route::post('/login', [
    AuthController::class,
    'login'
])->middleware('throttle:login');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/me', [
        AuthController::class,
        'me'
    ]);

    Route::post('/logout', [
        AuthController::class,
        'logout'
    ]);

    Route::apiResource('kelas', KelasController::class)
        ->parameters([
            'kelas' => 'kelas',
        ]);

    Route::apiResource('admin', AdminController::class);
    Route::get('guru/mapel/{mapel}', [GuruController::class, 'byMapel']);
    Route::apiResource('guru', GuruController::class);
    Route::get('jadwal/guru/{guru}', [JadwalController::class, 'byGuru']);
    Route::get('jadwal/kelas/{kelas}', [JadwalController::class, 'byKelas']);
    Route::post('jadwal/batch', [JadwalController::class, 'batchStore']);
    Route::apiResource('jadwal', JadwalController::class);

    Route::get('siswa/kelas/{kelas}', [SiswaController::class, 'byKelas']);
    Route::apiResource('siswa', SiswaController::class);

    Route::get('presensi/tanggal/{tanggal}', [PresensiController::class, 'byTanggal']);
    Route::apiResource('presensi', PresensiController::class);

    Route::apiResource('mata-pelajaran', MataPelajaranController::class)
        ->parameters([
            'mata-pelajaran' => 'mataPelajaran',
        ]);
    Route::apiResource('guru-mapel', GuruMapelController::class)
        ->parameters([
            'guru-mapel' => 'guruMapel',
        ]);
    Route::apiResource('embedding', EmbeddingController::class);
    Route::apiResource('users', UserController::class);

    Route::prefix('spk')->group(function () {
        Route::get('/ranking-guru', [SpkRankingController::class, 'guru']);
        Route::get('/ranking-siswa', [SpkRankingController::class, 'siswa']);
        Route::apiResource('criteria', SpkCriterionController::class)
            ->parameters(['criteria' => 'criterion']);
        Route::apiResource('scores', SpkScoreController::class)
            ->parameters(['scores' => 'score']);
    });
});
