<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Exception;

class ImportMoviebox extends Command
{
    protected $signature = 'app:import-moviebox {file=database/nazaarab_moviebox.sql}';
    protected $description = 'Import a large SQL file into the database with table renaming';

    public function handle()
    {
        $file = $this->argument('file');
        if (!file_exists($file)) {
            $this->error("File not found: $file");
            return 1;
        }

        $this->info("Starting import from $file...");

        $handle = fopen($file, "r");
        if (!$handle) {
            $this->error("Could not open file.");
            return 1;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        $query = '';
        $lineCount = 0;
        $batchCount = 0;

        $replacements = [
            'categories' => 'movie_categories',
            'users' => 'movie_users',
            'migrations' => 'movie_migrations',
            'roles' => 'movie_roles',
            'permissions' => 'movie_permissions',
            'password_resets' => 'movie_password_resets',
            'personal_access_tokens' => 'movie_tokens',
            'sessions' => 'movie_sessions',
            'cache' => 'movie_cache',
            'tags' => 'movie_tags',
            'failed_jobs' => 'movie_failed_jobs'
        ];

        while (($line = fgets($handle)) !== false) {
            $lineCount++;
            if ($lineCount % 10000 === 0) {
                $this->info("Processed $lineCount lines...");
            }

            $line = trim($line);
            if (empty($line) || str_starts_with($line, '--') || str_starts_with($line, '/*')) {
                continue;
            }

            foreach ($replacements as $old => $new) {
                $line = preg_replace('/`' . preg_quote($old, '/') . '`/', '`' . $new . '`', $line);
            }

            $query .= $line . "\n";

            if (str_ends_with($line, ';')) {
                try {
                    DB::unprepared($query);
                } catch (Exception $e) {
                    if (str_contains($e->getMessage(), 'already exists')) {
                        $this->warn("Table or record on line $lineCount already exists, skipping...");
                    } else {
                        $this->error("Error on line $lineCount: " . $e->getMessage());
                        $this->info("Query snippet: " . substr($query, 0, 100));
                        if ($this->confirm('Continue anyway?', true)) {
                            // continue
                        } else {
                            fclose($handle);
                            return 1;
                        }
                    }
                }
                $query = '';
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        fclose($handle);

        $this->info("Import completed successfully!");
        return 0;
    }
}
