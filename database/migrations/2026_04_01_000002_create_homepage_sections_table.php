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
        Schema::create('homepage_sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('category_ids')->nullable(); // Array of category IDs
            $table->integer('articles_per_section')->default(4);
            $table->string('section_title')->nullable(); // Custom title override
            $table->timestamps();
            
            $table->index(['is_active', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('homepage_sections');
    }
};
