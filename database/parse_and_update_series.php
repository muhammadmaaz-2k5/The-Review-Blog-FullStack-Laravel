<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$sqlFile = 'nazaarac_nc.sql';
$handle = fopen($sqlFile, "r");

$inInsert = false;
$buffer = '';

while (($line = fgets($handle)) !== false) {
    if (strpos($line, 'INSERT INTO `articles`') === 0) {
        $inInsert = true;
        // The first line is "INSERT INTO `articles` (...) VALUES "
        continue;
    }
    
    if ($inInsert) {
        $buffer .= $line;
        if (substr(trim($line), -1) === ';') {
            break;
        }
    }
}
fclose($handle);

echo "Loaded insert statement. Parsing...\n";

// Now we parse the buffer.
// It's a sequence of (val1, val2, ...), (val1, val2, ...);
$len = strlen($buffer);
$inString = false;
$stringChar = '';
$inTuple = false;
$currentTuple = [];
$currentValue = '';
$escaped = false;

$updates = [];

for ($i = 0; $i < $len; $i++) {
    $char = $buffer[$i];
    
    if ($escaped) {
        $currentValue .= $char;
        $escaped = false;
        continue;
    }
    
    if ($char === '\\') {
        $escaped = true;
        $currentValue .= $char;
        continue;
    }
    
    if (!$inString && ($char === "'" || $char === '"')) {
        $inString = true;
        $stringChar = $char;
        $currentValue .= $char;
        continue;
    }
    
    if ($inString && $char === $stringChar) {
        $inString = false;
        $currentValue .= $char;
        continue;
    }
    
    if (!$inString) {
        if ($char === '(' && !$inTuple) {
            $inTuple = true;
            $currentTuple = [];
            $currentValue = '';
            continue;
        }
        
        if ($char === ')' && $inTuple) {
            $currentTuple[] = trim($currentValue);
            $inTuple = false;
            
            // Process tuple
            if (count($currentTuple) >= 28) {
                $id = trim($currentTuple[0], "'\"");
                $series_id = trim($currentTuple[26], "'\"");
                $series_order = trim($currentTuple[27], "'\"");
                
                if ($series_id !== 'NULL' && is_numeric($series_id)) {
                    $updates[] = [
                        'id' => $id,
                        'series_id' => $series_id,
                        'series_order' => $series_order === 'NULL' ? 0 : $series_order
                    ];
                }
            }
            continue;
        }
        
        if ($char === ',' && $inTuple) {
            $currentTuple[] = trim($currentValue);
            $currentValue = '';
            continue;
        }
    }
    
    if ($inTuple) {
        $currentValue .= $char;
    }
}

echo "Found " . count($updates) . " articles with series_id.\n";

$count = 0;
foreach ($updates as $update) {
    DB::table('articles')->where('id', $update['id'])->update([
        'series_id' => $update['series_id'],
        'series_order' => $update['series_order']
    ]);
    $count++;
    if ($count % 10 == 0) {
        echo "Updated $count...\n";
    }
}

echo "Done! Updated $count articles.\n";
