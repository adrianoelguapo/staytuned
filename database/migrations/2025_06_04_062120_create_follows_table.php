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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('follower_id'); // Usuario que sigue
            $table->morphs('followable'); // Polimórfica: followable_id y followable_type
            $table->timestamp('followed_at')->useCurrent();
            $table->timestamps();

            // Índices para optimizar las consultas (morphs() ya crea un índice compuesto)
            $table->index(['follower_id', 'followable_type', 'followable_id']);
            $table->index('follower_id');

            // Clave foránea
            $table->foreign('follower_id')->references('id')->on('users')->onDelete('cascade');

            // Prevenir duplicados - un usuario no puede seguir al mismo usuario dos veces
            $table->unique(['follower_id', 'followable_type', 'followable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follows');
    }
};
