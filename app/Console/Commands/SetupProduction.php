<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class SetupProduction extends Command
{
    protected $signature = 'setup:production {--import : Attempt to import the 468MB MovieBox SQL file}';
    protected $description = 'Initialize or synchronize the production database for both Blog and MovieBox';

    public function handle()
    {
        $this->info("🚀 Starting Production Database Setup...");

        $this->syncMigrationHistory();

        $this->info("📝 Running pending migrations...");
        try {
            $this->call('migrate', ['--force' => true]);
        } catch (\Exception $e) {
            $this->error("Migration failed: " . $e->getMessage());
        }

        if ($this->option('import')) {
            if ($this->confirm('The 468MB import can take several minutes and may timeout. Proceed?', true)) {
                $this->call('app:import-moviebox');
            }
        }

        $this->info("✅ Production setup complete!");
        return 0;
    }

    /**
     * Synchronize the migration history with existing tables.
     * This prevents "table already exists" errors when migrating an existing database.
     */
    protected function syncMigrationHistory()
    {
        $this->info("🔍 Synchronizing migration history with existing tables...");
        
        $tables = Schema::getTables();
        $tableNames = array_map(function($table) {
            // Support both array and object returns from getTables() depending on Laravel version
            return is_array($table) ? ($table['name'] ?? reset($table)) : (is_object($table) ? ($table->name ?? $table->TableName) : $table);
        }, $tables);

        $migrationsPath = database_path('migrations');
        $files = scandir($migrationsPath);
        
        $syncedCount = 0;

        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            
            $content = file_get_contents($migrationsPath . '/' . $file);
            // Match Schema::create('table_name' or Schema::create("table_name"
            if (preg_match("/Schema::create\(['\"]([^'\"]+)['\"]/", $content, $matches)) {
                $tableName = $matches[1];
                
                if (in_array($tableName, $tableNames)) {
                    $migrationName = str_replace('.php', '', $file);
                    
                    // Check if already in migrations table
                    $exists = DB::table('migrations')->where('migration', $migrationName)->exists();
                    
                    if (!$exists) {
                        DB::table('migrations')->insert([
                            'migration' => $migrationName,
                            'batch' => 1
                        ]);
                        $this->line("  [SYNCED] Marked $migrationName as migrated (table '$tableName' already exists)");
                        $syncedCount++;
                    }
                }
            }
        }

        $this->info("✨ Synced $syncedCount migration records.");
    }
}
