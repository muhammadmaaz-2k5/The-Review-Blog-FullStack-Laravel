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
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('avatar')->nullable()->after('email');
            $table->text('bio')->nullable()->after('avatar');
            $table->string('website')->nullable()->after('bio');
            $table->string('twitter')->nullable()->after('website');
            $table->string('github')->nullable()->after('twitter');
            $table->string('linkedin')->nullable()->after('github');
            $table->boolean('is_author')->default(false)->after('linkedin');
            $table->string('role')->default('user')->after('is_author'); // user, author, admin
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'avatar',
                'bio',
                'website',
                'twitter',
                'github',
                'linkedin',
                'is_author',
                'role',
            ]);
        });
    }
};

