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
        Schema::create('reading_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->integer('progress')->default(0); // Reading progress percentage (0-100)
            $table->timestamp('last_read_at')->useCurrent();
            $table->timestamps();
            
            $table->unique(['user_id', 'article_id']); // One history entry per user per article
            $table->index('user_id');
            $table->index('article_id');
            $table->index('last_read_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reading_history');
    }
};

