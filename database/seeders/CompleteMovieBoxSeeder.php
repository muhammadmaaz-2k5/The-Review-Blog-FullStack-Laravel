<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class CompleteMovieBoxSeeder extends Seeder
{
    public function run()
    {
        try {
            $this->command->info('Starting MovieBox complete data migration...');
            
            // Get the SQL file path
            $sqlFile = database_path('seeders/nazaarab_moviebox.sql');
            
            if (!file_exists($sqlFile)) {
                $this->command->error('SQL file not found: ' . $sqlFile);
                $this->command->info('Please ensure nazaarab_moviebox.sql is in the database/seeders/ directory');
                return;
            }
            
            $this->command->info('Reading SQL file...');
            $sqlContent = file_get_contents($sqlFile);
            
            if ($sqlContent === false) {
                $this->command->error('Failed to read SQL file');
                return;
            }
            
            // Split SQL into individual statements
            $statements = $this->splitSqlStatements($sqlContent);
            
            $this->command->info('Found ' . count($statements) . ' SQL statements');
            
            // Process each statement
            $processed = 0;
            $errors = 0;
            
            foreach ($statements as $index => $statement) {
                try {
                    if (!empty(trim($statement))) {
                        DB::statement($statement);
                        $processed++;
                        
                        if ($processed % 100 == 0) {
                            $this->command->info("Processed {$processed} statements...");
                        }
                    }
                } catch (\Exception $e) {
                    $errors++;
                    Log::warning("SQL Statement Error #" . ($index + 1) . ": " . $e->getMessage());
                    
                    // Continue processing other statements
                }
            }
            
            $this->command->info('MovieBox migration completed!');
            $this->command->info("✅ Processed: {$processed} statements");
            $this->command->info("⚠️  Errors: {$errors} statements");
            
            // Verify tables were created
            $tables = ['dramas', 'episodes', 'movie_categories', 'audio_languages', 'languages'];
            $createdTables = [];
            
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    $count = DB::table($table)->count();
                    $createdTables[] = "{$table}: {$count} records";
                }
            }
            
            if (!empty($createdTables)) {
                $this->command->info('📊 Created tables:');
                foreach ($createdTables as $tableInfo) {
                    $this->command->info("   - {$tableInfo}");
                }
            }
            
        } catch (\Exception $e) {
            $this->command->error('Migration failed: ' . $e->getMessage());
            Log::error('MovieBox seeder failed: ' . $e->getMessage());
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
