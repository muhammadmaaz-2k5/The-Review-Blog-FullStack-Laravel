<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "Importing temp_articles.sql...\n";
    $sql = file_get_contents('temp_articles.sql');
    DB::unprepared($sql);
    echo "Imported.\n";
    
    $count = DB::table('temp_articles')->count();
    echo "temp_articles has $count rows.\n";
    
    $seriesCount = DB::table('temp_articles')->whereNotNull('series_id')->count();
    echo "temp_articles has $seriesCount rows with series_id.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
