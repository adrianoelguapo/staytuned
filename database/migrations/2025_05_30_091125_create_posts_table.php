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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('likes')->default(0)->comment('Nº de likes del post');
            $table->string('cover')->nullable()->comment('Ruta a la imagen del post');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate()->comment('Propietario del post');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate()->comment('Categoría del post');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
