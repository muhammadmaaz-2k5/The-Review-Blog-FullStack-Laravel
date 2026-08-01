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
        Schema::create('tips', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('content');
            $table->string('status')->default('pending'); // pending, reviewed, approved, rejected
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('email');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tips');
    }
};

