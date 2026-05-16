<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {

            $table->id();

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->onDelete('cascade');

            $table->foreignId('jadwal_id')
                ->constrained('jadwal')
                ->onDelete('cascade');

            $table->foreignId('guru_id')
                ->constrained('guru')
                ->onDelete('cascade');

            // hadir, izin, sakit, alpha
            $table->string('status', 20);

            $table->string('tanggal', 10);

            $table->string('jam_presensi', 10);

            // unique presensi
            $table->unique(
                ['siswa_id', 'jadwal_id', 'tanggal'],
                'uq_presensi_siswa_jadwal_tanggal'
            );

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};