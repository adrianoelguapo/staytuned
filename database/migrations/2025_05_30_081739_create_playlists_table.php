<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Nombre de la playlist');
            $table->unsignedInteger('likes')->default(0)->comment('Likes de la playlist');
            $table->unsignedInteger('length')->default(0)->comment('Número de canciones que tiene la playlist');
            $table->string('cover')->nullable()->comment('Ruta a la imagen de la playlist');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate()->comment('Propietario de la playlist');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};
