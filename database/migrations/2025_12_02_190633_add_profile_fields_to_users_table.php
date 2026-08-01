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
        Schema::table('users', function (Blueprint $table) {
            // Additional profile fields
            $table->string('location')->nullable()->after('linkedin');
            $table->string('cover_image')->nullable()->after('avatar');
            $table->boolean('profile_public')->default(true)->after('role'); // Make profile publicly visible
            $table->timestamp('last_activity_at')->nullable()->after('profile_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'location',
                'cover_image',
                'profile_public',
                'last_activity_at',
            ]);
        });
    }
};
