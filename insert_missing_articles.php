<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Get columns of articles and temp_articles
    $articlesColumns = DB::getSchemaBuilder()->getColumnListing('articles');
    $tempColumns = DB::getSchemaBuilder()->getColumnListing('temp_articles');
    
    // Find intersection to avoid inserting into missing columns
    $commonColumns = array_intersect($articlesColumns, $tempColumns);
    $columnsList = implode(', ', array_map(function($c) { return "`$c`"; }, $commonColumns));
    
    // Also update existing ones just in case
    echo "Updating existing articles...\n";
    $affected = DB::update("UPDATE articles a JOIN temp_articles ta ON a.id = ta.id SET a.series_id = ta.series_id, a.series_order = ta.series_order");
    echo "Updated $affected rows.\n";

    echo "Inserting missing articles...\n";
    $query = "INSERT INTO articles ($columnsList) SELECT $columnsList FROM temp_articles WHERE id NOT IN (SELECT id FROM articles)";
    $inserted = DB::insert($query);
    echo "Inserted missing articles.\n";
    
    $total = DB::table('articles')->count();
    $seriesCount = DB::table('articles')->whereNotNull('series_id')->count();
    
    echo "Total articles now: $total\n";
    echo "Articles with series_id: $seriesCount\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
