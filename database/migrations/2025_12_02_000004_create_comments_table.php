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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained('articles')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name')->nullable(); // For guest comments
            $table->string('email')->nullable(); // For guest comments
            $table->text('content');
            $table->foreignId('parent_id')->nullable()->constrained('comments')->onDelete('cascade'); // For replies
            $table->string('status')->default('approved'); // approved, pending, spam, rejected
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('article_id');
            $table->index('status');
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

