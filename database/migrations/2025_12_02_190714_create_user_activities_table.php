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
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // article_published, article_updated, comment_posted, etc.
            $table->string('description'); // Human-readable description
            $table->morphs('subject'); // subject_id and subject_type (polymorphic)
            $table->json('metadata')->nullable(); // Additional data
            $table->timestamp('created_at');
            
            // Indexes for efficient queries
            $table->index('user_id');
            $table->index('type');
            $table->index(['subject_id', 'subject_type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_activities');
    }
};
