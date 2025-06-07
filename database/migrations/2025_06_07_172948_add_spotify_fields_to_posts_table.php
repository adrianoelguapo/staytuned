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
        Schema::table('posts', function (Blueprint $table) {
            $table->text('description')->nullable()->after('content')->comment('Descripción del post');
            $table->string('spotify_id')->nullable()->comment('ID de Spotify (canción, artista, álbum)');
            $table->string('spotify_type')->nullable()->comment('Tipo de contenido Spotify (track, artist, album)');
            $table->string('spotify_external_url')->nullable()->comment('URL externa de Spotify');
            $table->json('spotify_data')->nullable()->comment('Datos completos de Spotify en JSON');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'spotify_id',
                'spotify_type',
                'spotify_external_url',
                'spotify_data'
            ]);
        });
    }
};
