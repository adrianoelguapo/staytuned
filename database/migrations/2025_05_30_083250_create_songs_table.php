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
        Schema::create('songs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->comment('Nombre de la canción');
            $table->time('duration')->nullable()->comment('Duración de la canción');
            $table->string('cover')->nullable()->comment('Ruta a donde se almacena la imagen de la playlist');
            $table->string('author')->nullable()->comment('Autor de la canción');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
