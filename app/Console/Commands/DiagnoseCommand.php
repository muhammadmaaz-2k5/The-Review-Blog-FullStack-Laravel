<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class DiagnoseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diagnose {--fix : Attempt to fix common issues}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnose and identify problems in the Laravel application';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('🔍 Laravel Application Diagnostics');
        $this->info('====================================');
        $this->newLine();

        $issues = [];
        $warnings = [];
        $successes = [];

        // 1. Check Environment File
        $this->line('📄 Checking environment configuration...');
        $envCheck = $this->checkEnvironment();
        $this->mergeResults($issues, $warnings, $successes, $envCheck);

        // 2. Check Required Directories
        $this->line('📁 Checking required directories...');
        $dirCheck = $this->checkDirectories();
        $this->mergeResults($issues, $warnings, $successes, $dirCheck);

        // 3. Check View Files
        $this->line('👁️  Checking view files...');
        $viewCheck = $this->checkViews();
        $this->mergeResults($issues, $warnings, $successes, $viewCheck);

        // 4. Check Composer Dependencies
        $this->line('📦 Checking Composer dependencies...');
        $composerCheck = $this->checkComposer();
        $this->mergeResults($issues, $warnings, $successes, $composerCheck);

        // 5. Check Database Connection
        $this->line('💾 Checking database connection...');
        $dbCheck = $this->checkDatabase();
        $this->mergeResults($issues, $warnings, $successes, $dbCheck);

        // 6. Check Storage Permissions
        $this->line('🔐 Checking storage permissions...');
        $permCheck = $this->checkPermissions();
        $this->mergeResults($issues, $warnings, $successes, $permCheck);

        // 7. Check Cache Status
        $this->line('💨 Checking cache status...');
        $cacheCheck = $this->checkCache();
        $this->mergeResults($issues, $warnings, $successes, $cacheCheck);

        // 8. Check Service Providers
        $this->line('⚙️  Checking service providers...');
        $providerCheck = $this->checkServiceProviders();
        $this->mergeResults($issues, $warnings, $successes, $providerCheck);

        // 9. Check Routes
        $this->line('🛣️  Checking routes...');
        $routeCheck = $this->checkRoutes();
        $this->mergeResults($issues, $warnings, $successes, $routeCheck);

        // 10. Check Key Files
        $this->line('🔑 Checking key configuration files...');
        $keyCheck = $this->checkKeyFiles();
        $this->mergeResults($issues, $warnings, $successes, $keyCheck);

        // Display Results
        $this->newLine();
        $this->displayResults($issues, $warnings, $successes);

        // Attempt fixes if requested
        if ($this->option('fix') && !empty($issues)) {
            $this->newLine();
            $this->attemptFixes($issues);
        }

        // Summary
        $this->newLine();
        $this->displaySummary(count($issues), count($warnings), count($successes));

        return count($issues) > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * Check environment configuration
     */
    protected function checkEnvironment(): array
    {
        $issues = [];
        $warnings = [];
        $successes = [];

        if (!file_exists(base_path('.env'))) {
            $issues[] = '❌ .env file is missing';
        } else {
            $successes[] = '✅ .env file exists';

            // Check APP_KEY
            if (empty(env('APP_KEY'))) {
                $issues[] = '❌ APP_KEY is not set in .env';
            } else {
                $successes[] = '✅ APP_KEY is configured';
            }

            // Check APP_ENV
            $appEnv = env('APP_ENV', 'local');
            if ($appEnv === 'production' && env('APP_DEBUG') === 'true') {
                $warnings[] = '⚠️  APP_DEBUG is enabled in production mode';
            } else {
                $successes[] = "✅ APP_ENV is set to: {$appEnv}";
            }

            // Check database configuration
            if (empty(env('DB_CONNECTION'))) {
                $warnings[] = '⚠️  DB_CONNECTION is not set';
            } else {
                $successes[] = '✅ Database connection type is configured';
            }
        }

        return compact('issues', 'warnings', 'successes');
    }

    /**
     * Check required directories
     */
    protected function checkDirectories(): array
    {
        $issues = [];
        $warnings = [];
        $successes = [];

        $requiredDirs = [
            'storage/app' => 'Storage app directory',
            'storage/framework' => 'Storage framework directory',
            'storage/framework/cache' => 'Storage cache directory',
            'storage/framework/sessions' => 'Storage sessions directory',
            'storage/framework/views' => 'Storage views directory',
            'storage/logs' => 'Storage logs directory',
            'bootstrap/cache' => 'Bootstrap cache directory',
            'resources/views' => 'Resources views directory',
        ];

        foreach ($requiredDirs as $dir => $name) {
            $path = base_path($dir);
            if (!is_dir($path)) {
                $issues[] = "❌ {$name} is missing: {$dir}";
            } else {
                $successes[] = "✅ {$name} exists";
            }
        }

        return compact('issues', 'warnings', 'successes');
    }

    /**
     * Check view files
     */
    protected function checkViews(): array
    {
        $issues = [];
        $warnings = [];
        $successes = [];

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

        foreach ($requiredViews as $view) {
            if (View::exists($view)) {
                $successes[] = "✅ View '{$view}' exists";
            } else {
                $issues[] = "❌ View '{$view}' is missing";
                
                // Check if file exists in expected location
                $path = resource_path('views/' . str_replace('.', '/', $view) . '.blade.php');
                if (file_exists($path)) {
                    $warnings[] = "⚠️  View file exists at {$path} but Laravel can't find it (cache issue?)";
                }
            }
        }

        return compact('issues', 'warnings', 'successes');
    }

    /**
     * Check Composer dependencies
     */
    protected function checkComposer(): array
    {
        $issues = [];
        $warnings = [];
        $successes = [];

        // Check if vendor directory exists
        if (!is_dir(base_path('vendor'))) {
            $issues[] = '❌ vendor directory is missing - run: composer install';
            return compact('issues', 'warnings', 'successes');
        }

        $successes[] = '✅ vendor directory exists';

        // Check for common dev dependencies that might cause issues in production
        $devDependencies = [
            'laravel/pail' => 'Pail (dev only)',
            'laravel/pint' => 'Pint (dev only)',
        ];

        foreach ($devDependencies as $package => $name) {
            $packagePath = base_path("vendor/{$package}");
            if (is_dir($packagePath)) {
                if (env('APP_ENV') === 'production') {
                    $warnings[] = "⚠️  {$name} is installed in production (should use --no-dev)";
                } else {
                    $successes[] = "✅ {$name} is installed (dev mode)";
                }
            }
        }

        // Check if composer autoload exists
        if (!file_exists(base_path('vendor/autoload.php'))) {
            $issues[] = '❌ Composer autoload file is missing';
        } else {
            $successes[] = '✅ Composer autoload file exists';
        }

        return compact('issues', 'warnings', 'successes');
    }

    /**
     * Check database connection
     */
    protected function checkDatabase(): array
    {
        $issues = [];
        $warnings = [];
        $successes = [];

        try {
            DB::connection()->getPdo();
            $successes[] = '✅ Database connection successful';

            // Check for required tables
            $requiredTables = ['users', 'articles', 'categories', 'tags'];
            foreach ($requiredTables as $table) {
                if (Schema::hasTable($table)) {
                    $successes[] = "✅ Table '{$table}' exists";
                } else {
                    $issues[] = "❌ Table '{$table}' is missing - run migrations";
                }
            }
        } catch (\Exception $e) {
            $issues[] = '❌ Database connection failed: ' . $e->getMessage();
            $warnings[] = '⚠️  Check DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD in .env';
        }

        return compact('issues', 'warnings', 'successes');
    }

    /**
     * Check storage permissions
     */
    protected function checkPermissions(): array
    {
        $issues = [];
        $warnings = [];
        $successes = [];

        $writablePaths = [
            'storage' => 'Storage directory',
            'storage/app' => 'Storage app directory',
            'storage/framework' => 'Storage framework directory',
            'storage/framework/cache' => 'Storage cache directory',
            'storage/framework/sessions' => 'Storage sessions directory',
            'storage/framework/views' => 'Storage views directory',
            'storage/logs' => 'Storage logs directory',
            'bootstrap/cache' => 'Bootstrap cache directory',
        ];

        foreach ($writablePaths as $path => $name) {
            $fullPath = base_path($path);
            if (!is_dir($fullPath)) {
                continue; // Already checked in directories
            }

            if (!is_writable($fullPath)) {
                $issues[] = "❌ {$name} is not writable: {$path}";
            } else {
                $successes[] = "✅ {$name} is writable";
            }
        }

        return compact('issues', 'warnings', 'successes');
    }

    /**
     * Check cache status
     */
    protected function checkCache(): array
    {
        $issues = [];
        $warnings = [];
        $successes = [];

        // Check for excessive cached views
        $viewCachePath = storage_path('framework/views');
        if (is_dir($viewCachePath)) {
            $cachedViews = count(glob($viewCachePath . '/*.php'));
            if ($cachedViews > 100) {
                $warnings[] = "⚠️  Large number of cached views ({$cachedViews}) - consider clearing";
            } else {
                $successes[] = "✅ View cache size is normal ({$cachedViews} files)";
            }
        }

        // Check if cache driver is available
        try {
            $cache = cache();
            $testKey = 'diagnostic_test_' . time();
            cache()->put($testKey, 'test', 1);
            $result = cache()->get($testKey);
            cache()->forget($testKey);
            
            if ($result === 'test') {
                $successes[] = '✅ Cache is working';
            } else {
                // If database cache, might need migrations
                if (config('cache.default') === 'database') {
                    $warnings[] = '⚠️  Database cache may need cache table migration';
                    $successes[] = '✅ Cache driver is configured (database)';
                } else {
                    $issues[] = '❌ Cache is not working properly';
                }
            }
        } catch (\Illuminate\Database\QueryException $e) {
            if (config('cache.default') === 'database') {
                $warnings[] = '⚠️  Cache table may not exist - run: php artisan cache:table';
                $warnings[] = '⚠️  Then run: php artisan migrate';
            } else {
                $warnings[] = '⚠️  Cache test failed: ' . $e->getMessage();
            }
        } catch (\Exception $e) {
            $warnings[] = '⚠️  Cache test failed: ' . $e->getMessage();
        }

        return compact('issues', 'warnings', 'successes');
    }

    /**
     * Check service providers
     */
    protected function checkServiceProviders(): array
    {
        $issues = [];
        $warnings = [];
        $successes = [];

        try {
            $providers = app()->getLoadedProviders();
            
            // Check for problematic dev-only providers
            if (isset($providers['Laravel\\Pail\\PailServiceProvider'])) {
                if (env('APP_ENV') === 'production') {
                    $issues[] = '❌ Pail service provider is loaded in production (dev dependency)';
                } else {
                    $successes[] = '✅ Pail service provider loaded (dev mode)';
                }
            }

            // Check for required providers
            $requiredProviders = [
                'App\\Providers\\AppServiceProvider' => 'AppServiceProvider',
            ];

            foreach ($requiredProviders as $provider => $name) {
                if (isset($providers[$provider])) {
                    $successes[] = "✅ {$name} is loaded";
                } else {
                    $warnings[] = "⚠️  {$name} is not loaded";
                }
            }
        } catch (\Exception $e) {
            $warnings[] = '⚠️  Could not check service providers: ' . $e->getMessage();
        }

        return compact('issues', 'warnings', 'successes');
    }

    /**
     * Check routes
     */
    protected function checkRoutes(): array
    {
        $issues = [];
        $warnings = [];
        $successes = [];

        try {
            $routes = \Illuminate\Support\Facades\Route::getRoutes();
            
            // Check for home route
            $homeRoute = $routes->getByName('home');
            if ($homeRoute) {
                $successes[] = '✅ Home route is registered';
            } else {
                $issues[] = '❌ Home route is missing';
            }

            // Count total routes
            $routeCount = $routes->count();
            if ($routeCount > 0) {
                $successes[] = "✅ {$routeCount} routes registered";
            } else {
                $issues[] = '❌ No routes found';
            }
        } catch (\Exception $e) {
            $warnings[] = '⚠️  Could not check routes: ' . $e->getMessage();
        }

        return compact('issues', 'warnings', 'successes');
    }

    /**
     * Check key configuration files
     */
    protected function checkKeyFiles(): array
    {
        $issues = [];
        $warnings = [];
        $successes = [];

        $keyFiles = [
            'composer.json' => 'Composer configuration',
            'package.json' => 'NPM configuration',
            'artisan' => 'Artisan command file',
            'public/index.php' => 'Public entry point',
            'routes/web.php' => 'Web routes',
            'bootstrap/app.php' => 'Bootstrap file',
        ];

        foreach ($keyFiles as $file => $name) {
            if (file_exists(base_path($file))) {
                $successes[] = "✅ {$name} exists";
            } else {
                $issues[] = "❌ {$name} is missing: {$file}";
            }
        }

        return compact('issues', 'warnings', 'successes');
    }

    /**
     * Merge results
     */
    protected function mergeResults(array &$issues, array &$warnings, array &$successes, array $check): void
    {
        $issues = array_merge($issues, $check['issues'] ?? []);
        $warnings = array_merge($warnings, $check['warnings'] ?? []);
        $successes = array_merge($successes, $check['successes'] ?? []);
    }

    /**
     * Display results
     */
    protected function displayResults(array $issues, array $warnings, array $successes): void
    {
        if (!empty($issues)) {
            $this->error('🚨 CRITICAL ISSUES FOUND:');
            foreach ($issues as $issue) {
                $this->line("  {$issue}");
            }
            $this->newLine();
        }

        if (!empty($warnings)) {
            $this->warn('⚠️  WARNINGS:');
            foreach ($warnings as $warning) {
                $this->line("  {$warning}");
            }
            $this->newLine();
        }

        if (!empty($successes)) {
            $this->info('✅ CHECKS PASSED:');
            foreach (array_slice($successes, 0, 10) as $success) {
                $this->line("  {$success}");
            }
            if (count($successes) > 10) {
                $this->line("  ... and " . (count($successes) - 10) . " more");
            }
        }
    }

    /**
     * Attempt to fix common issues
     */
    protected function attemptFixes(array $issues): void
    {
        $this->info('🔧 Attempting to fix issues...');
        $this->newLine();

        $fixed = 0;
        $hasViewIssues = false;
        $hasKeyIssues = false;
        $hasCacheIssues = false;

        // Check what needs fixing
        foreach ($issues as $issue) {
            if (str_contains($issue, 'View') && str_contains($issue, 'missing')) {
                $hasViewIssues = true;
            }
            if (str_contains($issue, 'APP_KEY is not set')) {
                $hasKeyIssues = true;
            }
            if (str_contains($issue, 'cache') || str_contains($issue, 'Cache')) {
                $hasCacheIssues = true;
            }
        }

        // Fix missing .env
        foreach ($issues as $issue) {
            if (str_contains($issue, '.env file is missing')) {
                if (file_exists(base_path('.env.example'))) {
                    copy(base_path('.env.example'), base_path('.env'));
                    $this->info('  ✅ Created .env file from .env.example');
                    $fixed++;
                }
            }
        }

        // Fix missing APP_KEY
        if ($hasKeyIssues) {
            try {
                $this->call('key:generate', ['--force' => true]);
                $this->info('  ✅ Generated APP_KEY');
                $fixed++;
            } catch (\Exception $e) {
                $this->warn('  ⚠️  Could not generate APP_KEY: ' . $e->getMessage());
            }
        }

        // Fix view and cache issues (most important for production)
        if ($hasViewIssues || $hasCacheIssues) {
            $this->info('  🔄 Clearing all caches to fix view finder issues...');
            
            try {
                // Clear all caches
                $this->call('optimize:clear');
                $this->info('  ✅ Cleared all caches (config, route, view, compiled, events)');
                $fixed++;
                
                // Clear view cache specifically
                $this->call('view:clear');
                $this->info('  ✅ Cleared view cache');
                
                // Clear config cache
                $this->call('config:clear');
                $this->info('  ✅ Cleared config cache');
                
                // Clear route cache
                $this->call('route:clear');
                $this->info('  ✅ Cleared route cache');
                
            } catch (\Exception $e) {
                $this->warn('  ⚠️  Error clearing caches: ' . $e->getMessage());
            }
        }

        if ($fixed > 0) {
            $this->newLine();
            $this->info("✅ Fixed {$fixed} issue(s). Please run 'php artisan diagnose' again.");
            $this->newLine();
            $this->warn('⚠️  IMPORTANT: If views are still missing, check:');
            $this->line('   1. View files exist in: ' . resource_path('views'));
            $this->line('   2. File permissions are correct');
            $this->line('   3. Run: php artisan view:clear');
            $this->line('   4. Run: php artisan optimize:clear');
        } else {
            $this->warn('⚠️  Could not automatically fix any issues.');
        }
    }

    /**
     * Display summary
     */
    protected function displaySummary(int $issues, int $warnings, int $successes): void
    {
        $this->info('📊 DIAGNOSTIC SUMMARY:');
        $this->line("  Critical Issues: {$issues}");
        $this->line("  Warnings: {$warnings}");
        $this->line("  Passed Checks: {$successes}");
        $this->newLine();

        if ($issues > 0) {
            $this->error('❌ Your application has critical issues that need attention.');
            $this->line('💡 Tip: Run with --fix flag to attempt automatic fixes: php artisan diagnose --fix');
        } elseif ($warnings > 0) {
            $this->warn('⚠️  Your application has some warnings but should work.');
        } else {
            $this->info('✅ All checks passed! Your application looks healthy.');
        }
    }
}

