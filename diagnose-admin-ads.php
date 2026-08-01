<?php

/**
 * Admin/Ads "Not Found" Diagnostic Script
 * 
 * Run on production server via: php diagnose-admin-ads.php
 * Or via web: https://yourdomain.com/diagnose-admin-ads.php
 * 
 * DELETE THIS FILE AFTER DIAGNOSIS - it exposes sensitive server info
 */

$isCli = php_sapi_name() === 'cli';

function output($title, $status, $message, $details = '')
{
    global $isCli;
    if ($isCli) {
        $icon = $status === 'PASS' ? '[OK]' : ($status === 'FAIL' ? '[FAIL]' : ($status === 'WARN' ? '[WARN]' : '[INFO]'));
        echo "$icon $title: $message\n";
        if ($details) {
            echo "   $details\n";
        }
        echo "\n";
    } else {
        $color = $status === 'PASS' ? '#22c55e' : ($status === 'FAIL' ? '#ef4444' : ($status === 'WARN' ? '#f59e0b' : '#3b82f6'));
        echo "<div style='margin:8px 0;padding:10px;border-left:4px solid $color;background:#1a1a1a;color:#e5e5e5;font-family:monospace;font-size:13px;'>";
        echo "<strong style='color:$color'>[$status]</strong> <strong>$title</strong>: $message";
        if ($details) {
            echo "<br><span style='color:#999;font-size:12px;'>$details</span>";
        }
        echo "</div>";
    }
}

function checkDir($path)
{
    return is_dir($path) && is_writable($path);
}

if (!$isCli) {
    echo "<html><head><title>Admin/Ads Diagnostic</title><style>body{background:#111;color:#fff;font-family:monospace;padding:20px;}</style></head><body>";
    echo "<h1 style='color:#f59e0b;'>Admin/Ads Not Found - Diagnostic Report</h1>";
    echo "<p style='color:#999;'>Generated: " . date('Y-m-d H:i:s') . "</p><hr>";
}

echo $isCli ? "=== Admin/Ads Not Found Diagnostic ===\n\n" : "";

// ============================================================
// 1. LARAVEL BOOTSTRAP CHECK
// ============================================================
$basePath = __DIR__;
$autoloader = $basePath . '/vendor/autoload.php';
$laravelBootstrapped = false;

if (file_exists($autoloader)) {
    require $autoloader;
    output('Autoloader', 'PASS', 'vendor/autoload.php loaded');
    
    try {
        $app = require $basePath . '/bootstrap/app.php';
        if ($app && $app instanceof \Illuminate\Foundation\Application) {
            $kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
            $request = \Illuminate\Http\Request::capture();
            $app->instance('request', $request);
            $kernel->bootstrap();
            $laravelBootstrapped = true;
            output('Laravel Bootstrap', 'PASS', 'Laravel application bootstrapped successfully');
        }
    } catch (\Throwable $e) {
        output('Laravel Bootstrap', 'FAIL', 'Laravel failed to bootstrap', $e->getMessage());
    }
} else {
    output('Autoloader', 'FAIL', 'vendor/autoload.php not found', 'Run: composer install');
}

// ============================================================
// 2. ENVIRONMENT CHECK
// ============================================================
echo $isCli ? "\n--- Environment ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Environment</h2>";

$envPath = $basePath . '/.env';
if (file_exists($envPath)) {
    $envContent = file_get_contents($envPath);
    output('.env File', 'PASS', '.env file exists');
    
    preg_match('/^APP_ENV=(.*)$/m', $envContent, $envMatch);
    $appEnv = trim($envMatch[1] ?? 'unknown');
    output('APP_ENV', $appEnv === 'production' ? 'PASS' : 'WARN', "APP_ENV=$appEnv", $appEnv !== 'production' ? 'Should be "production" on production server' : '');
    
    preg_match('/^APP_DEBUG=(.*)$/m', $envContent, $debugMatch);
    $appDebug = trim($debugMatch[1] ?? 'unknown');
    output('APP_DEBUG', $appDebug === 'false' ? 'PASS' : 'WARN', "APP_DEBUG=$appDebug", $appDebug !== 'false' ? 'Should be "false" on production for security' : '');
    
    preg_match('/^APP_URL=(.*)$/m', $envContent, $urlMatch);
    $appUrl = trim($urlMatch[1] ?? 'unknown');
    $isLocalUrl = str_contains($appUrl, '127.0.0.1') || str_contains($appUrl, 'localhost');
    output('APP_URL', !$isLocalUrl ? 'PASS' : 'FAIL', "APP_URL=$appUrl", $isLocalUrl ? 'CRITICAL: APP_URL points to localhost. This breaks session cookies, route generation, and CSRF. Must be set to the production URL (e.g., https://nazaaracircle.com)' : '');
    
    preg_match('/^SESSION_DRIVER=(.*)$/m', $envContent, $sessionMatch);
    $sessionDriver = trim($sessionMatch[1] ?? 'unknown');
    output('SESSION_DRIVER', $sessionDriver !== 'file' ? 'PASS' : 'WARN', "SESSION_DRIVER=$sessionDriver", $sessionDriver === 'file' ? 'File sessions may not work with load balancers. Consider "database" or "redis" for production.' : '');
    
    preg_match('/^SESSION_DOMAIN=(.*)$/m', $envContent, $sessionDomainMatch);
    $sessionDomain = trim($sessionDomainMatch[1] ?? 'null');
    output('SESSION_DOMAIN', $sessionDomain !== 'null' ? 'PASS' : 'WARN', "SESSION_DOMAIN=$sessionDomain", $sessionDomain === 'null' ? 'If using HTTPS, set SESSION_DOMAIN=.nazaaracircle.com (with leading dot)' : '');
} else {
    output('.env File', 'FAIL', '.env file not found');
}

// ============================================================
// 3. DATABASE & MIGRATION CHECK
// ============================================================
echo $isCli ? "\n--- Database & Migrations ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Database & Migrations</h2>";

if ($laravelBootstrapped) {
    try {
        $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
        output('Database Connection', 'PASS', 'Database connected: ' . \Illuminate\Support\Facades\DB::connection()->getDatabaseName());
    } catch (\Throwable $e) {
        output('Database Connection', 'FAIL', 'Cannot connect to database', $e->getMessage());
    }
    
    // Check if ads table exists
    try {
        $adsTableExists = \Illuminate\Support\Facades\Schema::hasTable('ads');
        if ($adsTableExists) {
            output('ads Table', 'PASS', 'ads table exists in database');
            
            $columns = \Illuminate\Support\Facades\Schema::getColumnListing('ads');
            $requiredColumns = ['id', 'name', 'slug', 'placement', 'type', 'ad_code', 'is_active', 'sort_order', 'description', 'created_at', 'updated_at'];
            $missingColumns = array_diff($requiredColumns, $columns);
            if (empty($missingColumns)) {
                output('ads Table Schema', 'PASS', 'All required columns present');
            } else {
                output('ads Table Schema', 'FAIL', 'Missing columns: ' . implode(', ', $missingColumns), 'Run: php artisan migrate');
            }
            
            $adCount = \Illuminate\Support\Facades\DB::table('ads')->count();
            output('ads Table Data', $adCount > 0 ? 'INFO' : 'WARN', "$adCount record(s) in ads table", $adCount === 0 ? 'Table is empty - this is OK if you haven\'t added ads yet' : '');
        } else {
            output('ads Table', 'FAIL', 'ads table does NOT exist in database', 'CRITICAL: Run "php artisan migrate" on production to create the ads table. The migration file exists at: database/migrations/2026_04_11_002639_create_ads_table.php');
        }
    } catch (\Throwable $e) {
        output('ads Table Check', 'FAIL', 'Cannot check ads table', $e->getMessage());
    }
    
    // Check migration status
    try {
        $migrations = \Illuminate\Support\Facades\DB::table('migrations')->pluck('migration')->toArray();
        $adsMigration = array_filter($migrations, fn($m) => str_contains($m, 'ads'));
        if (!empty($adsMigration)) {
            output('Ads Migration', 'PASS', 'Ads migration recorded: ' . implode(', ', $adsMigration));
        } else {
            output('Ads Migration', 'FAIL', 'Ads migration NOT recorded in migrations table', 'The migration file exists but hasn\'t been run. Execute: php artisan migrate');
        }
    } catch (\Throwable $e) {
        output('Migration Status', 'WARN', 'Cannot check migration status', $e->getMessage());
    }
}

// ============================================================
// 4. ROUTE CHECK
// ============================================================
echo $isCli ? "\n--- Routes ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Routes</h2>";

if ($laravelBootstrapped) {
    try {
        $routes = \Illuminate\Support\Facades\Route::getRoutes();
        $adsRoutes = [];
        $adminRoutes = [];
        
        foreach ($routes as $route) {
            $uri = $route->uri();
            if (str_contains($uri, 'admin/ads') || str_contains($uri, 'admin.ads')) {
                $adsRoutes[] = $route->methods()[0] . ' ' . $uri . ' -> ' . $route->getName();
            }
            if (str_starts_with($uri, 'admin') && !str_contains($uri, 'articles') && !str_contains($uri, 'categories') && !str_contains($uri, 'tags')) {
                if (str_contains($uri, 'ads')) {
                    $adminRoutes[] = $route->methods()[0] . ' ' . $uri;
                }
            }
        }
        
        if (!empty($adsRoutes)) {
            output('Ads Routes', 'PASS', count($adsRoutes) . ' ads route(s) registered');
            foreach ($adsRoutes as $r) {
                output('  Route', 'INFO', $r);
            }
        } else {
            output('Ads Routes', 'FAIL', 'NO ads routes registered! This is the cause of 404.', 'Possible fixes: 1) php artisan route:clear  2) php artisan route:cache  3) Check that routes/web.php has the ads resource route');
        }
        
        // Check admin dashboard route
        $hasAdminDashboard = false;
        foreach ($routes as $route) {
            if ($route->getName() === 'admin.dashboard') {
                $hasAdminDashboard = true;
                break;
            }
        }
        output('Admin Dashboard Route', $hasAdminDashboard ? 'PASS' : 'FAIL', $hasAdminDashboard ? 'admin.dashboard route exists' : 'admin.dashboard route missing');
        
    } catch (\Throwable $e) {
        output('Route Check', 'FAIL', 'Cannot check routes', $e->getMessage());
    }
}

// Check route cache
$routeCachePath = $basePath . '/bootstrap/cache/routes-v7.php';
if (file_exists($routeCachePath)) {
    output('Route Cache', 'WARN', 'Route cache file exists', 'If routes were added after caching, run: php artisan route:clear && php artisan route:cache');
} else {
    $altCachePaths = glob($basePath . '/bootstrap/cache/routes*.php');
    if (!empty($altCachePaths)) {
        output('Route Cache', 'WARN', 'Route cache file found: ' . basename($altCachePaths[0]), 'Run: php artisan route:clear');
    } else {
        output('Route Cache', 'PASS', 'No route cache file found (routes loaded dynamically)');
    }
}

// ============================================================
// 5. CACHE CHECK
// ============================================================
echo $isCli ? "\n--- Cache ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Cache</h2>";

$configCachePath = $basePath . '/bootstrap/cache/config.php';
if (file_exists($configCachePath)) {
    output('Config Cache', 'WARN', 'Config cache file exists', 'If .env was changed after caching, run: php artisan config:clear');
} else {
    output('Config Cache', 'PASS', 'No config cache file found');
}

$eventsCachePath = $basePath . '/bootstrap/cache/events.php';
if (file_exists($eventsCachePath)) {
    output('Events Cache', 'INFO', 'Events cache file exists');
}

// Check compiled views
$viewsPath = $basePath . '/storage/framework/views';
if (is_dir($viewsPath)) {
    $compiledViews = glob($viewsPath . '/*.php');
    output('Compiled Views', 'INFO', count($compiledViews) . ' compiled view(s)', 'Run: php artisan view:clear if views are stale');
}

// ============================================================
// 6. VIEW FILES CHECK
// ============================================================
echo $isCli ? "\n--- View Files ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>View Files</h2>";

$requiredViews = [
    'resources/views/admin/ads/index.blade.php',
    'resources/views/admin/ads/create.blade.php',
    'resources/views/admin/ads/edit.blade.php',
    'resources/views/layouts/app.blade.php',
    'resources/views/errors/404.blade.php',
];

foreach ($requiredViews as $view) {
    $fullPath = $basePath . '/' . $view;
    if (file_exists($fullPath)) {
        output("View: $view", 'PASS', 'File exists');
    } else {
        output("View: $view", 'FAIL', 'File MISSING', 'This view file is required but was not found');
    }
}

// Missing show.blade.php check
$showView = $basePath . '/resources/views/admin/ads/show.blade.php';
if (file_exists($showView)) {
    output('show.blade.php', 'INFO', 'show.blade.php exists');
} else {
    output('show.blade.php', 'WARN', 'show.blade.php does NOT exist', 'Route::resource generates a "show" route but the view is missing. This causes 404 for GET /admin/ads/{id}. Add a show method to AdController or exclude show from the resource route.');
}

// ============================================================
// 7. CONTROLLER CHECK
// ============================================================
echo $isCli ? "\n--- Controller ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Controller</h2>";

$controllerPath = $basePath . '/app/Http/Controllers/Admin/AdController.php';
if (file_exists($controllerPath)) {
    output('AdController', 'PASS', 'File exists');
    
    $controllerContent = file_get_contents($controllerPath);
    $requiredMethods = ['index', 'create', 'store', 'edit', 'update', 'destroy'];
    foreach ($requiredMethods as $method) {
        $hasMethod = preg_match('/function\s+' . $method . '\s*\(/', $controllerContent);
        output("  $method()", $hasMethod ? 'PASS' : 'FAIL', $hasMethod ? 'Method exists' : 'Method MISSING');
    }
    
    $hasShow = preg_match('/function\s+show\s*\(/', $controllerContent);
    output('  show()', $hasShow ? 'PASS' : 'WARN', $hasShow ? 'Method exists' : 'Method MISSING - Route::resource will generate a show route that returns 404');
    
    $hasToggle = preg_match('/function\s+toggle\s*\(/', $controllerContent);
    output('  toggle()', $hasToggle ? 'PASS' : 'FAIL', $hasToggle ? 'Method exists' : 'Method MISSING');
    
    $hasToggleAll = preg_match('/function\s+toggleAll\s*\(/', $controllerContent);
    output('  toggleAll()', $hasToggleAll ? 'PASS' : 'FAIL', $hasToggleAll ? 'Method exists' : 'Method MISSING');
} else {
    output('AdController', 'FAIL', 'File NOT found', 'CRITICAL: app/Http/Controllers/Admin/AdController.php does not exist');
}

// ============================================================
// 8. MODEL CHECK
// ============================================================
echo $isCli ? "\n--- Model ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Model</h2>";

$modelPath = $basePath . '/app/Models/Ad.php';
if (file_exists($modelPath)) {
    output('Ad Model', 'PASS', 'File exists');
    
    $modelContent = file_get_contents($modelPath);
    $hasFillable = preg_match('/\$fillable/', $modelContent);
    $hasPlacementOptions = preg_match('/\$placementOptions/', $modelContent);
    $hasTypeOptions = preg_match('/\$typeOptions/', $modelContent);
    
    output('  $fillable', $hasFillable ? 'PASS' : 'FAIL', $hasFillable ? 'Defined' : 'Not defined');
    output('  $placementOptions', $hasPlacementOptions ? 'PASS' : 'FAIL', $hasPlacementOptions ? 'Defined' : 'Not defined - AdController relies on this');
    output('  $typeOptions', $hasTypeOptions ? 'PASS' : 'FAIL', $hasTypeOptions ? 'Defined' : 'Not defined - AdController relies on this');
} else {
    output('Ad Model', 'FAIL', 'File NOT found', 'CRITICAL: app/Models/Ad.php does not exist');
}

// ============================================================
// 9. MIDDLEWARE CHECK
// ============================================================
echo $isCli ? "\n--- Middleware ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Middleware</h2>";

$isAdminPath = $basePath . '/app/Http/Middleware/IsAdmin.php';
if (file_exists($isAdminPath)) {
    output('IsAdmin Middleware', 'PASS', 'File exists');
    
    $middlewareContent = file_get_contents($isAdminPath);
    $checksAuth = preg_match('/auth\(\)->check\(\)/', $middlewareContent);
    $checksIsAdmin = preg_match('/isAdmin\(\)/', $middlewareContent);
    
    output('  Auth Check', $checksAuth ? 'PASS' : 'WARN', $checksAuth ? 'Checks authentication' : 'May not check auth properly');
    output('  isAdmin Check', $checksIsAdmin ? 'PASS' : 'WARN', $checksIsAdmin ? 'Checks admin role' : 'May not check admin role properly');
} else {
    output('IsAdmin Middleware', 'FAIL', 'File NOT found');
}

// Check middleware registration
$bootstrapAppPath = $basePath . '/bootstrap/app.php';
if (file_exists($bootstrapAppPath)) {
    $bootstrapContent = file_get_contents($bootstrapAppPath);
    $adminAliasRegistered = preg_match("/'admin'.*IsAdmin/", $bootstrapContent);
    output('Middleware Alias', $adminAliasRegistered ? 'PASS' : 'FAIL', $adminAliasRegistered ? 'admin middleware alias registered' : 'admin middleware alias NOT registered in bootstrap/app.php');
}

// ============================================================
// 10. PUBLIC DIRECTORY CONFLICT
// ============================================================
echo $isCli ? "\n--- Public Directory Conflict ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Public Directory Conflict</h2>";

$publicAdsDir = $basePath . '/public/ads';
if (is_dir($publicAdsDir)) {
    $files = glob($publicAdsDir . '/*');
    output('public/ads/ Directory', 'WARN', 'public/ads/ directory EXISTS with ' . count($files) . ' file(s)', 'POTENTIAL CONFLICT: Apache may serve files from public/ads/ directly. While this shouldn\'t conflict with /admin/ads, some server configurations (especially cPanel) may incorrectly route requests. Files: ' . implode(', ', array_map('basename', $files)));
} else {
    output('public/ads/ Directory', 'PASS', 'No public/ads/ directory (no conflict)');
}

// ============================================================
// 11. .HTACCESS CHECK
// ============================================================
echo $isCli ? "\n--- .htaccess ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>.htaccess</h2>";

$rootHtaccess = $basePath . '/.htaccess';
if (file_exists($rootHtaccess)) {
    $htaccessContent = file_get_contents($rootHtaccess);
    output('Root .htaccess', 'PASS', 'Exists');
    
    $hasPublicRewrite = preg_match('/RewriteRule.*public/', $htaccessContent);
    output('  Public Rewrite', $hasPublicRewrite ? 'PASS' : 'WARN', $hasPublicRewrite ? 'Rewrites to public/' : 'No public/ rewrite rule found');
    
    $hasFrontController = preg_match('/RewriteRule.*index\.php/', $htaccessContent);
    output('  Front Controller', $hasFrontController ? 'PASS' : 'WARN', $hasFrontController ? 'Has index.php front controller rule' : 'No front controller rule found');
}

$publicHtaccess = $basePath . '/public/.htaccess';
if (file_exists($publicHtaccess)) {
    output('Public .htaccess', 'PASS', 'Exists');
    $pubHtaccessContent = file_get_contents($publicHtaccess);
    $hasDirectoryCheck = preg_match('/REQUEST_FILENAME.*!-d/', $pubHtaccessContent);
    $hasFileCheck = preg_match('/REQUEST_FILENAME.*!-f/', $pubHtaccessContent);
    output('  Directory Check', $hasDirectoryCheck ? 'PASS' : 'FAIL', $hasDirectoryCheck ? 'Skips existing directories' : 'MISSING: !-d check');
    output('  File Check', $hasFileCheck ? 'PASS' : 'FAIL', $hasFileCheck ? 'Skips existing files' : 'MISSING: !-f check');
}

// ============================================================
// 12. FILE PERMISSIONS
// ============================================================
echo $isCli ? "\n--- File Permissions ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>File Permissions</h2>";

$checkPaths = [
    'storage' => $basePath . '/storage',
    'storage/framework' => $basePath . '/storage/framework',
    'storage/framework/views' => $basePath . '/storage/framework/views',
    'storage/framework/cache' => $basePath . '/storage/framework/cache',
    'storage/framework/sessions' => $basePath . '/storage/framework/sessions',
    'storage/logs' => $basePath . '/storage/logs',
    'bootstrap/cache' => $basePath . '/bootstrap/cache',
];

foreach ($checkPaths as $name => $path) {
    if (is_dir($path)) {
        $writable = is_writable($path);
        $perms = substr(sprintf('%o', fileperms($path)), -4);
        output($name, $writable ? 'PASS' : 'FAIL', "Exists ($perms)" . ($writable ? ' writable' : ' NOT writable'), $writable ? '' : 'Fix: chmod 755 ' . $path);
    } else {
        output($name, 'FAIL', 'Directory NOT found', 'Missing required directory');
    }
}

// ============================================================
// 13. SESSION CHECK
// ============================================================
echo $isCli ? "\n--- Sessions ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Sessions</h2>";

if ($laravelBootstrapped) {
    $sessionDriver = config('session.driver');
    output('Session Driver', 'INFO', "Configured: $sessionDriver");
    
    if ($sessionDriver === 'file') {
        $sessionPath = storage_path('framework/sessions');
        if (is_dir($sessionPath)) {
            $sessionFiles = glob($sessionPath . '/*');
            output('Session Files', 'INFO', count($sessionFiles) . ' session file(s)');
        } else {
            output('Session Directory', 'FAIL', 'Session storage directory missing', "Create it: mkdir -p $sessionPath && chmod 755 $sessionPath");
        }
    }
}

// ============================================================
// 14. STORAGE LINK CHECK
// ============================================================
echo $isCli ? "\n--- Storage Link ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Storage Link</h2>";

$publicStorage = $basePath . '/public/storage';
if (is_link($publicStorage) || is_dir($publicStorage)) {
    output('Storage Link', 'PASS', 'public/storage exists');
} else {
    output('Storage Link', 'WARN', 'public/storage not found', 'Run: php artisan storage:link');
}

// ============================================================
// DIAGNOSIS SUMMARY
// ============================================================
echo $isCli ? "\n=== DIAGNOSIS SUMMARY ===\n" : "<hr><h2 style='color:#f59e0b;margin-top:20px;'>Diagnosis Summary & Fix Steps</h2>";

$fixSteps = <<<HTML
<div style='background:#1e293b;padding:20px;border-radius:8px;margin:10px 0;'>
<h3 style='color:#ef4444;'>Most Likely Causes of "Not Found" on Production:</h3>
<ol style='color:#e5e5e5;line-height:2;'>
<li><strong style='color:#f59e0b;'>Migration not run</strong> - The ads table doesn't exist on production DB</li>
<li><strong style='color:#f59e0b;'>Route cache stale</strong> - Old cached routes don't include the new ads routes</li>
<li><strong style='color:#f59e0b;'>APP_URL mismatch</strong> - APP_URL=http://127.0.0.1:8000 breaks session cookies and CSRF on production</li>
<li><strong style='color:#f59e0b;'>Config/view cache stale</strong> - Cached config or compiled views are outdated</li>
<li><strong style='color:#f59e0b;'>OPcache serving old code</strong> - PHP OPcache is caching old controller/route code</li>
</ol>

<h3 style='color:#22c55e;margin-top:20px;'>Fix Steps (run on production server via SSH):</h3>
<ol style='color:#e5e5e5;line-height:2.2;'>
<li><code style='background:#333;padding:2px 8px;border-radius:4px;'>php artisan down</code> - Put site in maintenance mode</li>
<li><code style='background:#333;padding:2px 8px;border-radius:4px;'>php artisan migrate</code> - Run pending migrations (creates ads table)</li>
<li>Fix .env: Set <code style='background:#333;padding:2px 8px;border-radius:4px;'>APP_URL=https://nazaaracircle.com</code></li>
<li>Fix .env: Set <code style='background:#333;padding:2px 8px;border-radius:4px;'>APP_ENV=production</code></li>
<li>Fix .env: Set <code style='background:#333;padding:2px 8px;border-radius:4px;'>APP_DEBUG=false</code></li>
<li><code style='background:#333;padding:2px 8px;border-radius:4px;'>php artisan config:clear</code></li>
<li><code style='background:#333;padding:2px 8px;border-radius:4px;'>php artisan route:clear</code></li>
<li><code style='background:#333;padding:2px 8px;border-radius:4px;'>php artisan view:clear</code></li>
<li><code style='background:#333;padding:2px 8px;border-radius:4px;'>php artisan cache:clear</code></li>
<li><code style='background:#333;padding:2px 8px;border-radius:4px;'>php artisan optimize:clear</code></li>
<li><code style='background:#333;padding:2px 8px;border-radius:4px;'>php artisan config:cache</code> - Re-cache config</li>
<li><code style='background:#333;padding:2px 8px;border-radius:4px;'>php artisan route:cache</code> - Re-cache routes</li>
<li><code style='background:#333;padding:2px 8px;border-radius:4px;'>php artisan view:cache</code> - Recompile views</li>
<li>If using cPanel, restart PHP or reload PHP-FPM to clear OPcache</li>
<li><code style='background:#333;padding:2px 8px;border-radius:4px;'>php artisan up</code> - Bring site back up</li>
</ol>

<h3 style='color:#3b82f6;margin-top:20px;'>Quick One-Liner Fix:</h3>
<code style='background:#333;padding:8px 12px;border-radius:4px;display:block;color:#22c55e;word-break:break-all;'>
php artisan down && php artisan migrate --force && php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan up
</code>

<h3 style='color:#f59e0b;margin-top:20px;'>Additional: Fix the show() route gap</h3>
<p style='color:#e5e5e5;'>Route::resource('ads', ...) generates a show route (GET /admin/ads/{ad}) but AdController has no show() method.<br>
Add to routes/web.php resource route: <code style='background:#333;padding:2px 8px;border-radius:4px;'>Route::resource('ads', AdController::class)->except(['show']);</code></p>
</div>
HTML;

if ($isCli) {
    echo "\nMost Likely Causes:\n";
    echo "1. Migration not run (ads table missing)\n";
    echo "2. Route cache stale\n";
    echo "3. APP_URL mismatch (127.0.0.1 instead of production URL)\n";
    echo "4. Config/view cache stale\n";
    echo "5. OPcache serving old code\n\n";
    echo "Fix Steps:\n";
    echo "1. php artisan down\n";
    echo "2. php artisan migrate --force\n";
    echo "3. Fix .env: APP_URL=https://nazaaracircle.com\n";
    echo "4. Fix .env: APP_ENV=production, APP_DEBUG=false\n";
    echo "5. php artisan optimize:clear\n";
    echo "6. php artisan config:cache && php artisan route:cache && php artisan view:cache\n";
    echo "7. Restart PHP/FPM to clear OPcache\n";
    echo "8. php artisan up\n\n";
    echo "Quick One-Liner:\n";
    echo "php artisan down && php artisan migrate --force && php artisan optimize:clear && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan up\n";
} else {
    echo $fixSteps;
}

// ============================================================
// 15. LIVE ROUTE TEST
// ============================================================
echo $isCli ? "\n--- Live Route Test ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Live Route Test</h2>";

if ($laravelBootstrapped) {
    try {
        $request = \Illuminate\Http\Request::create('/admin/ads', 'GET');
        $request->setRequestFormat('html');
        
        $router = app('router');
        $route = $router->getRoutes()->match($request);
        
        if ($route) {
            output('Route Match', 'PASS', 'Route found for /admin/ads', 'URI: ' . $route->uri() . ' | Name: ' . $route->getName() . ' | Action: ' . $route->getAction('uses'));
            
            $middleware = $route->gatherMiddleware();
            output('Middleware', 'INFO', 'Applied middleware: ' . implode(', ', $middleware));
            
            if (!in_array('auth', $middleware) && !in_array('admin', $middleware)) {
                output('Middleware', 'WARN', 'auth/admin middleware not applied to this route');
            }
        }
    } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
        output('Route Match', 'FAIL', '/admin/ads route NOT FOUND - this confirms the 404', 'The route is not registered in Laravel. Clear route cache and check routes/web.php.');
    } catch (\Throwable $e) {
        output('Route Match', 'WARN', 'Cannot test route match', $e->getMessage());
    }
}

// ============================================================
// 16. TEST CREATE PAGE SPECIFICALLY
// ============================================================
echo $isCli ? "\n--- Create Page Test ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Create Page Test (/admin/ads/create)</h2>";

if ($laravelBootstrapped) {
    try {
        $request = \Illuminate\Http\Request::create('/admin/ads/create', 'GET');
        $request->setRequestFormat('html');
        
        $router = app('router');
        $route = $router->getRoutes()->match($request);
        
        if ($route) {
            output('Create Route Match', 'PASS', 'Route found for /admin/ads/create', 'URI: ' . $route->uri() . ' | Name: ' . $route->getName() . ' | Action: ' . $route->getAction('uses'));
        }
    } catch (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e) {
        output('Create Route Match', 'FAIL', '/admin/ads/create route NOT FOUND', 'Clear route cache: php artisan route:clear && php artisan route:cache');
    } catch (\Throwable $e) {
        output('Create Route Match', 'WARN', 'Cannot test create route', $e->getMessage());
    }
    
    try {
        $controller = new \App\Http\Controllers\Admin\AdController();
        $reflection = new \ReflectionClass($controller);
        output('AdController Instance', 'PASS', 'Can instantiate AdController');
        
        $placementOptions = \App\Models\Ad::$placementOptions;
        $typeOptions = \App\Models\Ad::$typeOptions;
        output('Placement Options', 'PASS', count($placementOptions) . ' placement options defined');
        output('Type Options', 'PASS', count($typeOptions) . ' type options defined');
    } catch (\Throwable $e) {
        output('Controller/Model Test', 'FAIL', 'Error accessing AdController or Ad model', get_class($e) . ': ' . $e->getMessage());
    }
    
    try {
        $viewFactory = app('view');
        $viewFactory->addNamespace('app', resource_path('views'));
        $view = $viewFactory->make('admin.ads.create', [
            'placementOptions' => \App\Models\Ad::$placementOptions,
            'typeOptions' => \App\Models\Ad::$typeOptions,
        ]);
        $rendered = $view->render();
        output('Create View Render', 'PASS', 'View rendered successfully (' . strlen($rendered) . ' bytes)');
    } catch (\Throwable $e) {
        output('Create View Render', 'FAIL', 'ERROR rendering admin.ads.create view', get_class($e) . ': ' . $e->getMessage() . "\nFile: " . $e->getFile() . ':' . $e->getLine());
    }
}

// ============================================================
// 17. READ LATEST LARAVEL ERROR LOG
// ============================================================
echo $isCli ? "\n--- Latest Laravel Errors ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Latest Laravel Errors</h2>";

$logPath = $basePath . '/storage/logs/laravel.log';
if (file_exists($logPath)) {
    $logSize = filesize($logPath);
    $readBytes = min($logSize, 50000);
    $logContent = '';
    
    if ($handle = fopen($logPath, 'r')) {
        fseek($handle, max(0, $logSize - $readBytes));
        $logContent = fread($handle, $readBytes);
        fclose($handle);
    }
    
    if (!empty($logContent)) {
        preg_match_all('/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\].*?(?:InvalidArgumentException|ErrorException|FatalError|Exception|Throwable|Server Error).*?(?=\[\d{4}-\d{2}-\d{2}|$)/s', $logContent, $matches);
        
        if (!empty($matches[0])) {
            $recentErrors = array_slice($matches[0], -3);
            foreach ($recentErrors as $i => $error) {
                $errorLines = explode("\n", trim($error));
                $firstLines = array_slice($errorLines, 0, 15);
                $errorText = implode("\n", $firstLines);
                if (strlen($errorText) > 2000) {
                    $errorText = substr($errorText, 0, 2000) . '...';
                }
                output('Error ' . ($i + 1), 'FAIL', 'Recent error found', $isCli ? $errorText : nl2br(htmlspecialchars($errorText)));
            }
        } else {
            $lines = explode("\n", $logContent);
            $lastLines = array_slice($lines, -30);
            $lastContent = implode("\n", $lastLines);
            if (strlen($lastContent) > 3000) {
                $lastContent = substr($lastContent, -3000);
            }
            output('Log Tail', 'INFO', 'Last 30 lines of log', $isCli ? $lastContent : nl2br(htmlspecialchars($lastContent)));
        }
    } else {
        output('Log File', 'INFO', 'Log file is empty');
    }
} else {
    output('Log File', 'WARN', 'No laravel.log file found at ' . $logPath);
}

// ============================================================
// 18. SERVER ENVIRONMENT
// ============================================================
echo $isCli ? "\n--- Server Environment ---\n" : "<h2 style='color:#3b82f6;margin-top:20px;'>Server Environment</h2>";

output('PHP Version', version_compare(PHP_VERSION, '8.2.0', '>=') ? 'PASS' : 'FAIL', PHP_VERSION, version_compare(PHP_VERSION, '8.2.0', '<') ? 'Laravel 12 requires PHP 8.2+' : '');

if (function_exists('opcache_get_status')) {
    $opcacheStatus = opcache_get_status(false);
    if ($opcacheStatus) {
        output('OPcache', 'WARN', 'OPcache is enabled', 'If code was updated without restarting PHP-FPM, OPcache may serve old code. Restart PHP-FPM or use opcache_reset().');
    } else {
        output('OPcache', 'PASS', 'OPcache not active');
    }
} else {
    output('OPcache', 'INFO', 'Cannot check OPcache status');
}

if (!$isCli) {
    $serverSoftware = $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown';
    output('Server Software', 'INFO', $serverSoftware);
    
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || ($_SERVER['SERVER_PORT'] ?? '') === '443';
    output('HTTPS', $https ? 'PASS' : 'WARN', $https ? 'Active' : 'Not active - sessions/cookies may not work properly without HTTPS');
    
    $httpHost = $_SERVER['HTTP_HOST'] ?? 'Unknown';
    output('HTTP_HOST', 'INFO', $httpHost);
}

echo $isCli ? "\n=== End Diagnostic ===\n" : "<hr><p style='color:#999;font-size:12px;'>Diagnostic complete. DELETE THIS FILE from the server after use.</p></body></html>";
