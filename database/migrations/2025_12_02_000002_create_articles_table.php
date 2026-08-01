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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable(); // Short description
            $table->longText('content'); // Full article content
            $table->string('featured_image')->nullable(); // Main image
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('author_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('status')->default('published'); // published, draft, scheduled
            $table->integer('views')->default(0);
            $table->integer('reading_time')->nullable(); // Estimated reading time in minutes
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->json('meta')->nullable(); // Additional metadata
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('status');
            $table->index('is_featured');
            $table->index('published_at');
            $table->index('category_id');
            $table->index('author_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

