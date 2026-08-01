<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;

class CheckViewsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'views:check {view?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if required views exist';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $view = $this->argument('view');
        
        if ($view) {
            return $this->checkSingleView($view);
        }
        
        $this->info('Checking required views...');
        $this->newLine();
        
        $requiredViews = [
            'home',
            'layouts.app',
            'articles.index',
            'articles.show',
            'categories.index',
            'categories.show',
            'tags.index',
            'tags.show',
            'pages.about',
            'pages.contact',
            'pages.privacy',
            'pages.terms',
        ];
        
        $allExist = true;
        $missing = [];
        
        foreach ($requiredViews as $viewName) {
            $exists = View::exists($viewName);
            if ($exists) {
                $this->line("✅ <info>{$viewName}</info>");
            } else {
                $this->line("❌ <error>{$viewName}</error> - MISSING");
                $allExist = false;
                $missing[] = $viewName;
            }
        }
        
        $this->newLine();
        
        if ($allExist) {
            $this->info('✅ All required views exist!');
            
            // Check view paths
            $paths = View::getFinder()->getPaths();
            $this->newLine();
            $this->info('View paths:');
            foreach ($paths as $path) {
                $this->line("  - {$path}");
            }
            
            return Command::SUCCESS;
        } else {
            $this->error('❌ Missing views detected!');
            $this->newLine();
            $this->warn('Missing views:');
            foreach ($missing as $view) {
                $this->line("  - {$view}");
            }
            $this->newLine();
            $this->info('To fix:');
            $this->line('  1. Ensure all view files are deployed to: ' . resource_path('views'));
            $this->line('  2. Run: php artisan view:clear');
            $this->line('  3. Run: php artisan optimize:clear');
            
            return Command::FAILURE;
        }
    }
    
    /**
     * Check a single view
     */
    protected function checkSingleView(string $view): int
    {
        $exists = View::exists($view);
        
        if ($exists) {
            $this->info("✅ View '{$view}' exists!");
            
            $paths = View::getFinder()->getPaths();
            $this->newLine();
            $this->info('View paths:');
            foreach ($paths as $path) {
                $fullPath = $path . '/' . str_replace('.', '/', $view) . '.blade.php';
                if (file_exists($fullPath)) {
                    $this->line("  ✅ {$fullPath}");
                } else {
                    $this->line("  ❌ {$fullPath} (not found)");
                }
            }
            
            return Command::SUCCESS;
        } else {
            $this->error("❌ View '{$view}' NOT FOUND!");
            
            $paths = View::getFinder()->getPaths();
            $this->newLine();
            $this->warn('Checked paths:');
            foreach ($paths as $path) {
                $fullPath = $path . '/' . str_replace('.', '/', $view) . '.blade.php';
                $this->line("  - {$fullPath}");
                if (file_exists($fullPath)) {
                    $this->line("    ✅ File exists!");
                } else {
                    $this->line("    ❌ File not found");
                }
            }
            
            return Command::FAILURE;
        }
    }
}

