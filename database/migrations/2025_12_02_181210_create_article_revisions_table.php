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
        Schema::create('article_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('featured_image')->nullable();
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->string('status'); // published, draft, scheduled
            $table->boolean('is_featured')->default(false);
            $table->boolean('allow_comments')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->json('meta')->nullable();
            $table->text('change_summary')->nullable(); // Summary of what changed
            $table->integer('revision_number')->default(1);
            $table->timestamps();
            
            $table->index('article_id');
            $table->index('created_by');
            $table->index('revision_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_revisions');
    }
};
