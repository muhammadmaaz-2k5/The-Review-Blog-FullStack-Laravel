<?php

$sourceFile = 'nazaarab_box.sql';
$targetFile = 'nazaarac_nc.sql';

// Tables that are missing from nazaarac_nc.sql
$missingTables = [
    'ad_settings',
    'audio_languages', 
    'clips',
    'contents',
    'dramas',
    'drama_requests',
    'drama_rewarded_ads',
    'episodes',
    'fcm_tokens',
    'homepage_sections',
    'languages',
    'link_visits',
    'movie_cache',
    'movie_categories',
    'movie_comments',
    'movie_failed_jobs',
    'movie_migrations',
    'movie_pages',
    'movie_sessions',
    'movie_settings',
    'movie_users',
    'pages',
    'rewarded_ad_unlocks',
    'settings'
];

echo "Starting extraction of missing tables...\n";

// Read source file line by line to handle large files
$sourceHandle = fopen($sourceFile, 'r');
if (!$sourceHandle) {
    die("Cannot open source file: $sourceFile\n");
}

$targetHandle = fopen($targetFile, 'a');
if (!$targetHandle) {
    die("Cannot open target file for appending: $targetFile\n");
}

// Write header
fwrite($targetHandle, "\n\n-- Missing tables and data extracted from nazaarab_box.sql\n");
fwrite($targetHandle, "-- Generated on " . date('Y-m-d H:i:s') . "\n\n");

$currentTable = null;
$inTableSection = false;
$buffer = '';
$tablesProcessed = [];

while (($line = fgets($sourceHandle)) !== false) {
    // Check for CREATE TABLE
    if (preg_match('/^CREATE TABLE `([^`]+)`/', $line, $matches)) {
        $tableName = $matches[1];
        
        if (in_array($tableName, $missingTables)) {
            $currentTable = $tableName;
            $inTableSection = true;
            $buffer = $line;
            echo "Processing table: $tableName\n";
        } else {
            $currentTable = null;
            $inTableSection = false;
        }
    }
    // Continue collecting CREATE TABLE or INSERT statements
    elseif ($inTableSection && $currentTable) {
        $buffer .= $line;
        
        // Check if statement ended
        if (strpos($line, ';') !== false) {
            // Check if this is an INSERT statement for a missing table
            if (preg_match('/^INSERT INTO `([^`]+)`/', $buffer, $matches)) {
                $insertTable = $matches[1];
                if (in_array($insertTable, $missingTables)) {
                    fwrite($targetHandle, $buffer . "\n");
                    if (!isset($tablesProcessed[$insertTable])) {
                        $tablesProcessed[$insertTable] = 0;
                    }
                    $tablesProcessed[$insertTable]++;
                }
            }
            // Check if this is CREATE TABLE for a missing table
            elseif (strpos($buffer, 'CREATE TABLE') === 0) {
                fwrite($targetHandle, $buffer . "\n");
                $tablesProcessed[$currentTable] = ($tablesProcessed[$currentTable] ?? 0) + 1;
            }
            
            $buffer = '';
            $inTableSection = false;
        }
    }
    // Check for INSERT statements (not following CREATE TABLE)
    elseif (preg_match('/^INSERT INTO `([^`]+)`/', $line, $matches)) {
        $insertTable = $matches[1];
        if (in_array($insertTable, $missingTables)) {
            $currentTable = $insertTable;
            $inTableSection = true;
            $buffer = $line;
        }
    }
}

fclose($sourceHandle);
fclose($targetHandle);

echo "\nExtraction completed!\n";
echo "Tables processed:\n";
foreach ($tablesProcessed as $table => $count) {
    echo "- $table: $count statements\n";
}

echo "\nSuccessfully appended missing tables and data to $targetFile\n";
?>
