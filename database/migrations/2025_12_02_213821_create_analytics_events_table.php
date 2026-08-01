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
        Schema::dropIfExists('analytics_events');
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->index();
            $table->string('event_name'); // e.g., 'click', 'share', 'subscribe', etc.
            $table->string('event_category')->nullable(); // e.g., 'engagement', 'conversion', 'navigation'
            $table->string('event_action')->nullable(); // e.g., 'click', 'form_submit'
            $table->string('event_label')->nullable(); // e.g., 'button_name', 'link_url'
            $table->morphs('eventable'); // eventable_id, eventable_type
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('page_path')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->json('metadata')->nullable(); // Additional event data
            $table->integer('value')->nullable(); // Numeric value for events (e.g., purchase amount)
            $table->timestamp('occurred_at')->useCurrent();
            $table->timestamps();
            
            $table->index('event_name');
            $table->index('event_category');
            $table->index(['eventable_id', 'eventable_type']);
            $table->index('occurred_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
