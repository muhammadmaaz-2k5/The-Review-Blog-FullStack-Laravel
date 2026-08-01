<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * SAFE MIGRATION: This only adds new nullable columns.
     * - Does NOT modify or remove any existing columns
     * - All new fields are nullable, so existing articles will work fine
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            // Basic Meta Tags
            $table->string('meta_title')->nullable()->after('excerpt');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->text('meta_keywords')->nullable()->after('meta_description');
            $table->string('meta_robots')->default('index, follow')->after('meta_keywords');
            
            // Open Graph Tags
            $table->string('og_title')->nullable()->after('meta_robots');
            $table->text('og_description')->nullable()->after('og_title');
            $table->string('og_image')->nullable()->after('og_description');
            $table->string('og_type')->default('article')->after('og_image');
            $table->string('og_url')->nullable()->after('og_type');
            
            // Twitter Card Tags
            $table->string('twitter_card')->default('summary_large_image')->after('og_url');
            $table->string('twitter_title')->nullable()->after('twitter_card');
            $table->text('twitter_description')->nullable()->after('twitter_title');
            $table->string('twitter_image')->nullable()->after('twitter_description');
            
            // Advanced SEO
            $table->string('canonical_url')->nullable()->after('twitter_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title', 'meta_description', 'meta_keywords', 'meta_robots',
                'og_title', 'og_description', 'og_image', 'og_type', 'og_url',
                'twitter_card', 'twitter_title', 'twitter_description', 'twitter_image',
                'canonical_url'
            ]);
        });
    }
};

