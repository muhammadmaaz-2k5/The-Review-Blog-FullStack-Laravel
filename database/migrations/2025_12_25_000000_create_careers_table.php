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
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('requirements')->nullable();
            $table->string('location')->nullable();
            $table->enum('type', ['full-time', 'part-time', 'contract', 'remote', 'internship'])->default('full-time');
            $table->string('department')->nullable();
            $table->enum('experience_level', ['entry', 'mid', 'senior', 'executive'])->nullable();
            $table->string('salary_range')->nullable();
            $table->date('application_deadline')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('is_active');
            $table->index('is_featured');
            $table->index('type');
            $table->index('application_deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};

