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
            $table->unsignedBigInteger('follower_id'); 
            $table->morphs('followable');
            $table->timestamp('followed_at')->useCurrent();
            $table->timestamps();
            $table->index(['follower_id', 'followable_type', 'followable_id']);
            $table->index('follower_id');
            $table->foreign('follower_id')->references('id')->on('users')->onDelete('cascade');
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
