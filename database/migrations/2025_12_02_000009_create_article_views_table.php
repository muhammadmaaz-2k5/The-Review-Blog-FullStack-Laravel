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
        Schema::create('article_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('country')->nullable();
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();
            
            $table->index('article_id');
            $table->index('user_id');
            $table->index('ip_address');
            $table->index('viewed_at');
            // Prevent duplicate views from same user/IP in short time (optional)
            $table->index(['article_id', 'ip_address', 'viewed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_views');
    }
};

