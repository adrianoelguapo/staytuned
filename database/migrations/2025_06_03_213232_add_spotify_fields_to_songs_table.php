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
        Schema::table('songs', function (Blueprint $table) {
            $table->string('spotify_id')->unique()->nullable()->after('id');
            $table->string('title')->nullable()->after('spotify_id');
            $table->string('artist')->nullable()->after('title');
            $table->string('album')->nullable()->after('artist');
            $table->string('album_image')->nullable()->after('album');
            $table->string('spotify_url')->nullable()->after('album_image');
            $table->string('preview_url')->nullable()->after('spotify_url');
            $table->string('duration_formatted')->nullable()->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            $table->dropColumn([
                'spotify_id',
                'title',
                'artist', 
                'album',
                'album_image',
                'spotify_url',
                'duration_formatted'
            ]);
        });
    }
};
