<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
foreach($tables as $t) {
    $vars = get_object_vars($t);
    $table = reset($vars);
    if (strpos($table, 'series') !== false) {
        echo $table . "\n";
    }
}
