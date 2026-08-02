<?php

/**
 * Complete MovieBox Migration Script
 * Standalone PHP script to migrate all MovieBox data from nazaarab_moviebox.sql
 * 
 * Usage: php complete_moviebox_migration.php
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

class MovieBoxMigration
{
    public function run()
    {
        echo "🎬 Starting MovieBox Complete Migration...\n";
        echo "==========================================\n\n";
        
        try {
            // Get the SQL file path
            $sqlFile = __DIR__ . '/database/seeders/nazaarab_moviebox.sql';
            
            if (!file_exists($sqlFile)) {
                echo "❌ ERROR: SQL file not found: {$sqlFile}\n";
                echo "Please ensure nazaarab_moviebox.sql is in the database/seeders/ directory\n";
                echo "You can place the SQL file at: " . __DIR__ . "/database/seeders/\n";
                return;
            }
            
            echo "📖 Reading SQL file...\n";
            $sqlContent = file_get_contents($sqlFile);
            
            if ($sqlContent === false) {
                echo "❌ ERROR: Failed to read SQL file\n";
                return;
            }
            
            echo "📝 Parsing SQL statements...\n";
            $statements = $this->splitSqlStatements($sqlContent);
            
            echo "✅ Found " . count($statements) . " SQL statements\n\n";
            
            // Process each statement
            $processed = 0;
            $errors = 0;
            $tables = [];
            
            echo "🚀 Executing migration...\n";
            
            foreach ($statements as $index => $statement) {
                try {
                    if (!empty(trim($statement))) {
                        DB::statement($statement);
                        $processed++;
                        
                        // Track table creation
                        if (stripos($statement, 'CREATE TABLE') !== false) {
                            if (preg_match('/CREATE TABLE\s*`?([^`\s]+)/i', $statement, $matches)) {
                                $tables[] = $matches[1];
                            }
                        }
                        
                        // Progress indicator
                        if ($processed % 100 == 0) {
                            echo "   Processed {$processed} statements...\n";
                        }
                    }
                } catch (\Exception $e) {
                    $errors++;
                    echo "⚠️  Warning in statement #" . ($index + 1) . ": " . $e->getMessage() . "\n";
                }
            }
            
            echo "\n🎉 Migration completed!\n";
            echo "==========================================\n";
            echo "✅ Processed: {$processed} statements\n";
            echo "⚠️  Errors: {$errors} statements\n\n";
            
            // Verify tables were created
            echo "📊 Verifying created tables...\n";
            $createdTables = [];
            
            $expectedTables = ['dramas', 'episodes', 'movie_categories', 'audio_languages', 'languages', 'ad_settings'];
            
            foreach ($expectedTables as $table) {
                if (Schema::hasTable($table)) {
                    try {
                        $count = DB::table($table)->count();
                        $createdTables[] = "✅ {$table}: {$count} records";
                    } catch (\Exception $e) {
                        $createdTables[] = "⚠️  {$table}: Table exists but count failed";
                    }
                } else {
                    $createdTables[] = "❌ {$table}: Not found";
                }
            }
            
            foreach ($createdTables as $tableInfo) {
                echo "   {$tableInfo}\n";
            }
            
            echo "\n🔗 Next steps:\n";
            echo "   1. Clear Laravel cache: php artisan cache:clear\n";
            echo "   2. Visit admin dashboard to see MovieBox statistics\n";
            echo "   3. Manage MovieBox content from admin panel\n";
            
        } catch (\Exception $e) {
            echo "❌ Migration failed: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n";
        }
    }
    
    private function splitSqlStatements($sql)
    {
        $statements = [];
        $currentStatement = '';
        $inString = false;
        $stringChar = '';
        $lines = explode("\n", $sql);
        
        foreach ($lines as $line) {
            $line = trim($line);
            
            // Skip comments and empty lines
            if (empty($line) || strpos($line, '--') === 0 || strpos($line, '/*') === 0) {
                continue;
            }
            
            // Process each character to handle strings properly
            for ($i = 0; $i < strlen($line); $i++) {
                $char = $line[$i];
                
                if (!$inString && ($char === '"' || $char === "'")) {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($inString && $char === $stringChar) {
                    $inString = false;
                }
                
                $currentStatement .= $char;
            }
            
            // Add line break to preserve formatting
            $currentStatement .= "\n";
            
            // Check for statement terminator (semicolon) outside of strings
            if (!$inString && strpos($line, ';') !== false) {
                $statement = trim($currentStatement);
                if (!empty($statement)) {
                    $statements[] = $statement;
                }
                $currentStatement = '';
            }
        }
        
        // Add any remaining statement
        $remaining = trim($currentStatement);
        if (!empty($remaining)) {
            $statements[] = $remaining;
        }
        
        return $statements;
    }
}

// Run the migration
$migration = new MovieBoxMigration();
$migration->run();
