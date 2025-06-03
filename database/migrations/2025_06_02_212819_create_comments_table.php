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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('likes')->default(0)->comment('Nº de likes que tiene el comentario');
            $table->text('text')->comment('Contenido textual del comentario');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate()->comment('Propietario del comentario');
            $table->foreignId('post_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate()->comment('Post del comentario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
