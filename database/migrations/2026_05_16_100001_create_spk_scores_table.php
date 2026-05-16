<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spk_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_criterion_id')
                ->constrained('spk_criteria')
                ->onDelete('cascade');
            $table->string('target_type', 20);
            $table->unsignedBigInteger('target_id');
            $table->decimal('nilai', 12, 4);
            $table->string('periode', 30)->nullable();
            $table->timestamps();

            $table->unique(
                ['spk_criterion_id', 'target_type', 'target_id', 'periode'],
                'uq_spk_scores_criterion_target_period'
            );
            $table->index(['target_type', 'target_id']);
            $table->index('periode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spk_scores');
    }
};
