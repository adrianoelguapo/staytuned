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
        Schema::create('community_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('community_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('message')->nullable(); // Mensaje opcional del usuario al solicitar
            $table->text('admin_message')->nullable(); // Mensaje opcional del admin al aprobar/rechazar
            $table->timestamp('responded_at')->nullable(); // Cuándo respondió el admin
            $table->timestamps();
            
            // Evitar solicitudes duplicadas
            $table->unique(['user_id', 'community_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_requests');
    }
};
