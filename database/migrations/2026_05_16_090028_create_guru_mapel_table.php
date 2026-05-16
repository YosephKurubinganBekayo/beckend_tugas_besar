<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru_mapel', function (Blueprint $table) {

            $table->id();

            $table->foreignId('guru_id')
                ->constrained('guru')
                ->onDelete('cascade');

            $table->foreignId('mapel_id')
                ->constrained('mata_pelajaran')
                ->onDelete('cascade');

            // unique pair
            $table->unique(
                ['guru_id', 'mapel_id'],
                'uq_guru_mapel_pair'
            );

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_mapel');
    }
};