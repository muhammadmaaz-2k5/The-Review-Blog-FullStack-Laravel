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
        Schema::create('featured_videos', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->string('title')->nullable();
            $blueprint->string('youtube_url');
            $blueprint->boolean('is_active')->default(true);
            $blueprint->integer('views')->default(0);
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_videos');
    }
};
