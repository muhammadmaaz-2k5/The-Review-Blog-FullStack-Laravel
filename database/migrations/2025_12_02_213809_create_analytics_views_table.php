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
        Schema::create('analytics_views', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index(); // Track user sessions
            $table->string('page_path'); // URL path
            $table->string('page_title')->nullable();
            $table->morphs('viewable'); // viewable_id, viewable_type (for articles, categories, etc.)
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable(); // Full referrer URL
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable(); // Chrome, Firefox, Safari, etc.
            $table->string('os')->nullable(); // Windows, Mac, Linux, iOS, Android
            $table->string('screen_resolution')->nullable(); // 1920x1080
            $table->integer('time_on_page')->nullable(); // Time in seconds
            $table->boolean('is_bounce')->default(false); // Single page session
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();
            
            $table->index('page_path');
            $table->index(['viewable_id', 'viewable_type']);
            $table->index('viewed_at');
            $table->index('country');
            $table->index('device_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_views');
    }
};
