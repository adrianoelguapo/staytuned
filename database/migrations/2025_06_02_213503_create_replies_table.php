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
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('likes')->default(0)->comment('Nº de likes que tiene la respuesta');
            $table->text('text')->comment('Contenido textual de la respuesta');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate()->comment('Propietario de la respuesta');
            $table->foreignId('comment_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate()->comment('Comentario de la respuesta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('replies');
    }
};
