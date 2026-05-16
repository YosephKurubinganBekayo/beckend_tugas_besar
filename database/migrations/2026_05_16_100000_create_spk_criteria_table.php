<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spk_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('target_type', 20);
            $table->string('kode', 30);
            $table->string('nama', 100);
            $table->decimal('bobot', 8, 4);
            $table->string('tipe', 10)->default('benefit');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['target_type', 'kode'], 'uq_spk_criteria_target_kode');
            $table->index(['target_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spk_criteria');
    }
};
