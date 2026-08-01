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
        Schema::dropIfExists('analytics_geographic');
        Schema::create('analytics_geographic', function (Blueprint $table) {
            $table->id();
            $table->string('country_code', 2)->index(); // ISO country code (US, GB, etc.)
            $table->string('country_name')->index();
            $table->string('region')->nullable(); // State/Province
            $table->string('city')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->integer('visits_count')->default(1);
            $table->integer('unique_visitors')->default(1);
            $table->integer('page_views')->default(1);
            $table->timestamp('first_seen_at')->useCurrent();
            $table->timestamp('last_seen_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['country_code', 'city']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_geographic');
    }
};
