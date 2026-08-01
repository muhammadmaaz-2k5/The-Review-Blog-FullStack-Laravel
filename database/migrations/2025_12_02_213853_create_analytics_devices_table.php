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
        Schema::dropIfExists('analytics_devices');
        Schema::create('analytics_devices', function (Blueprint $table) {
            $table->id();
            $table->string('device_type')->index(); // desktop, mobile, tablet
            $table->string('browser')->nullable()->index(); // Chrome, Firefox, Safari, Edge
            $table->string('browser_version')->nullable();
            $table->string('os')->nullable()->index(); // Windows, macOS, Linux, iOS, Android
            $table->string('os_version')->nullable();
            $table->string('screen_resolution')->nullable()->index(); // 1920x1080
            $table->boolean('is_mobile')->default(false)->index();
            $table->boolean('is_tablet')->default(false)->index();
            $table->boolean('is_desktop')->default(false)->index();
            $table->integer('visits_count')->default(1);
            $table->integer('unique_visitors')->default(1);
            $table->integer('page_views')->default(1);
            $table->timestamp('first_seen_at')->useCurrent();
            $table->timestamp('last_seen_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['device_type', 'browser']);
            $table->index(['os', 'device_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_devices');
    }
};
