<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateWebsiteNameCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-name {--old=Nazaara Circle} {--new=Nazaara Circle}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update website name from Nazaara Circle to Nazaara Circle across the codebase';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $oldName = $this->option('old');
        $newName = $this->option('new');
        
        $this->info("🔄 Updating website name from '{$oldName}' to '{$newName}'...");
        $this->newLine();
        
        $directories = [
            'app',
            'resources/views',
            'database/seeders',
            'public/css',
        ];
        
        $extensions = ['php', 'blade.php', 'css', 'js'];
        $updated = 0;
        $files = 0;
        
        foreach ($directories as $dir) {
            $path = base_path($dir);
            if (!is_dir($path)) {
                continue;
            }
            
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($path)
            );
            
            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $extension = $file->getExtension();
                    if (in_array($extension, $extensions)) {
                        $files++;
                        $content = file_get_contents($file->getPathname());
                        $originalContent = $content;
                        
                        // Replace old name with new name
                        $content = str_replace($oldName, $newName, $content);
                        
                        if ($content !== $originalContent) {
                            file_put_contents($file->getPathname(), $content);
                            $updated++;
                            $this->line("✅ Updated: {$file->getPathname()}");
                        }
                    }
                }
            }
        }
        
        // Update .env file
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            $originalEnv = $envContent;
            
            // Update APP_NAME if it exists
            $envContent = preg_replace(
                '/^APP_NAME=.*$/m',
                "APP_NAME=\"{$newName}\"",
                $envContent
            );
            
            if ($envContent !== $originalEnv) {
                file_put_contents($envPath, $envContent);
                $updated++;
                $this->line("✅ Updated: .env (APP_NAME)");
            }
        }
        
        $this->newLine();
        $this->info("📊 Summary:");
        $this->line("   Files scanned: {$files}");
        $this->line("   Files updated: {$updated}");
        $this->newLine();
        
        if ($updated > 0) {
            $this->info("✅ Website name updated successfully!");
            $this->warn("⚠️  Please review the changes and test your application.");
        } else {
            $this->info("ℹ️  No files needed updating (name may already be correct).");
        }
        
        return 0;
    }
}

