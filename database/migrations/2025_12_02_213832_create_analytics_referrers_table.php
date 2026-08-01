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
        Schema::dropIfExists('analytics_referrers');
        Schema::create('analytics_referrers', function (Blueprint $table) {
            $table->id();
            $table->string('referrer_url')->index(); // Full referrer URL
            $table->string('referrer_domain')->index(); // Extracted domain
            $table->string('referrer_type')->nullable(); // search_engine, social, direct, external
            $table->string('search_engine')->nullable(); // google, bing, yahoo, etc.
            $table->string('search_query')->nullable(); // Search term if from search engine
            $table->string('social_platform')->nullable(); // facebook, twitter, linkedin, etc.
            $table->integer('visits_count')->default(1);
            $table->integer('unique_visitors')->default(1);
            $table->timestamp('first_seen_at')->useCurrent();
            $table->timestamp('last_seen_at')->useCurrent();
            $table->timestamps();
            
            $table->index('referrer_type');
            $table->index('search_engine');
            $table->index('social_platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_referrers');
    }
};
