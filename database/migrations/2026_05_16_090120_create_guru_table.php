<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru', function (Blueprint $table) {

            $table->id();

            $table->string('nama', 100);

            $table->string('jenis_kelamin', 20)
                ->nullable();

            $table->string('email', 100)
                ->unique();

            $table->string('password');

            $table->string('nip', 50)
                ->unique();

            $table->text('foto_url')
                ->nullable();

            $table->rememberToken();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};