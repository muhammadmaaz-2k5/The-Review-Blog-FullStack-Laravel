<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TruncateTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "Starting table truncation...\n";

        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Truncate tables in correct order (child tables first)
        
        // 1. Article-related tables
        DB::table('article_revisions')->truncate();
        echo "✓ article_revisions truncated\n";
        
        DB::table('article_tag')->truncate();
        echo "✓ article_tag truncated\n";
        
        DB::table('article_likes')->truncate();
        echo "✓ article_likes truncated\n";
        
        DB::table('article_views')->truncate();
        echo "✓ article_views truncated\n";
        
        DB::table('articles')->truncate();
        echo "✓ articles truncated\n";

        // 2. Tag table
        DB::table('tags')->truncate();
        echo "✓ tags truncated\n";

        // 3. Category table
        DB::table('categories')->truncate();
        echo "✓ categories truncated\n";

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        echo "\n✅ All specified tables have been emptied successfully!\n";
        echo "Note: Users, comments, bookmarks, and other data remain intact.\n";
    }
}
