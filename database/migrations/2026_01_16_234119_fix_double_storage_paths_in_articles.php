<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('articles')
            ->where('featured_image', 'like', '/storage/%')
            ->update([
                'featured_image' => DB::raw("REPLACE(featured_image, '/storage/', '')")
            ]);

        DB::table('articles')
            ->where('og_image', 'like', '/storage/%')
            ->update([
                'og_image' => DB::raw("REPLACE(og_image, '/storage/', '')")
            ]);

        DB::table('articles')
            ->where('twitter_image', 'like', '/storage/%')
            ->update([
                'twitter_image' => DB::raw("REPLACE(twitter_image, '/storage/', '')")
            ]);


        DB::table('users')
            ->where('avatar', 'like', '/storage/%')
            ->update([
                'avatar' => DB::raw("REPLACE(avatar, '/storage/', '')")
            ]);

        DB::table('users')
            ->where('cover_image', 'like', '/storage/%')
            ->update([
                'cover_image' => DB::raw("REPLACE(cover_image, '/storage/', '')")
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse this without potential data corruption if some paths were already correct
    }
};
