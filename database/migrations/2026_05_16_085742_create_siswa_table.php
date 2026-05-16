<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {

            $table->id();

            $table->string('nama', 100);

            $table->string('nis', 50)
                ->unique();

            $table->string('jenis_kelamin', 20)
                ->nullable();

            $table->foreignId('kelas_id')
                ->constrained('kelas')
                ->onDelete('cascade');

            $table->string('alamat', 255)
                ->nullable();

            $table->string('foto_url', 255)
                ->nullable();

            $table->string('embedding_status', 30)
                ->default('belum_diproses');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};