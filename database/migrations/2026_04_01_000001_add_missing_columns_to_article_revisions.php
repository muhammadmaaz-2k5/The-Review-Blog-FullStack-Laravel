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
        Schema::table('article_revisions', function (Blueprint $table) {
            // Add slug column if it doesn't exist
            if (!Schema::hasColumn('article_revisions', 'slug')) {
                $table->string('slug')->after('title');
            }
            
            // Add article status fields
            if (!Schema::hasColumn('article_revisions', 'status')) {
                $table->string('status')->default('draft')->after('category_id');
            }
            
            if (!Schema::hasColumn('article_revisions', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
            
            if (!Schema::hasColumn('article_revisions', 'allow_comments')) {
                $table->boolean('allow_comments')->default(true)->after('is_featured');
            }
            
            if (!Schema::hasColumn('article_revisions', 'published_at')) {
                $table->timestamp('published_at')->nullable()->after('allow_comments');
            }
            
            // Add meta field for storing additional metadata
            if (!Schema::hasColumn('article_revisions', 'meta')) {
                $table->json('meta')->nullable()->after('published_at');
            }
            
            // Rename changes_summary to change_summary if needed
            if (Schema::hasColumn('article_revisions', 'changes_summary')) {
                $table->renameColumn('changes_summary', 'change_summary');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('article_revisions', function (Blueprint $table) {
            $table->dropColumn(['slug', 'status', 'is_featured', 'allow_comments', 'published_at', 'meta']);
            
            // Rename change_summary back if rolling back
            if (Schema::hasColumn('article_revisions', 'change_summary')) {
                $table->renameColumn('change_summary', 'changes_summary');
            }
        });
    }
};
