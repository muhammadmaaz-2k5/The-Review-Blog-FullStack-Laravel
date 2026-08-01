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
        Schema::dropIfExists('analytics_sessions');
        Schema::create('analytics_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique()->index();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('entry_page')->nullable(); // First page visited
            $table->string('exit_page')->nullable(); // Last page visited
            $table->integer('page_views')->default(0);
            $table->integer('duration')->default(0); // Session duration in seconds
            $table->boolean('is_bounce')->default(true); // Single page session
            $table->timestamp('started_at')->useCurrent();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            
            $table->index('session_id');
            $table->index('started_at');
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_sessions');
    }
};
