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
        Schema::create('content_moderation_logs', function (Blueprint $table) {
            $table->id();
            $table->string('model_type'); // Tipo de modelo (App\Models\Post, App\Models\Playlist, etc.)
            $table->unsignedBigInteger('model_id'); // ID del modelo
            $table->string('field_name'); // Nombre del campo moderado (title, content, description, etc.)
            $table->text('original_content'); // Contenido original
            $table->text('moderated_content'); // Contenido moderado
            $table->json('offensive_words'); // Palabras ofensivas detectadas
            $table->string('ip_address')->nullable(); // IP del usuario
            $table->string('user_agent')->nullable(); // User Agent
            $table->unsignedBigInteger('user_id')->nullable(); // Usuario que creó el contenido
            $table->timestamps();

            // Índices para mejorar el rendimiento de las consultas
            $table->index(['model_type', 'model_id']);
            $table->index('user_id');
            $table->index('created_at');

            // Clave foránea para el usuario
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_moderation_logs');
    }
};
