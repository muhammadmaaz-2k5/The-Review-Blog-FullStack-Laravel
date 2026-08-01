<?php

$sourceFile = 'nazaarab_box.sql';
$targetFile = 'nazaarac_nc.sql';

// Tables that exist in nazaarab_box.sql but missing from nazaarac_nc.sql
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

echo "Extracting missing tables from $sourceFile...\n";

// Read source file
$sourceContent = file_get_contents($sourceFile);
if ($sourceContent === false) {
    die("Failed to read source file: $sourceFile\n");
}

// Split content by lines
$lines = explode("\n", $sourceContent);
$totalLines = count($lines);

echo "Source file has $totalLines lines\n";

$extractedContent = "";
$currentTable = null;
$inCreateTable = false;
$inInsert = false;
$tableCreateContent = "";
$tableInsertContent = "";

for ($i = 0; $i < $totalLines; $i++) {
    $line = $lines[$i];
    
    // Check for CREATE TABLE
    if (preg_match('/^CREATE TABLE `([^`]+)`/', $line, $matches)) {
        $currentTable = $matches[1];
        if (in_array($currentTable, $missingTables)) {
            $inCreateTable = true;
            $tableCreateContent = $line . "\n";
            echo "Found CREATE TABLE for missing table: $currentTable\n";
        } else {
            $inCreateTable = false;
            $currentTable = null;
        }
    }
    // Continue collecting CREATE TABLE content
    elseif ($inCreateTable && $currentTable) {
        $tableCreateContent .= $line . "\n";
        if (strpos($line, ';') !== false) {
            // CREATE TABLE statement ended
            $inCreateTable = false;
        }
    }
    
    // Check for INSERT statements for missing tables
    if (preg_match('/^INSERT INTO `([^`]+)`/', $line, $matches)) {
        $insertTable = $matches[1];
        if (in_array($insertTable, $missingTables)) {
            $inInsert = true;
            if (!isset($tableInsertContent[$insertTable])) {
                $tableInsertContent[$insertTable] = "";
            }
            $tableInsertContent[$insertTable] .= $line . "\n";
        } else {
            $inInsert = false;
        }
    }
    // Continue collecting INSERT content
    elseif ($inInsert && $currentTable) {
        $tableInsertContent[$currentTable] .= $line . "\n";
        if (strpos($line, ';') !== false) {
            // INSERT statement ended
            $inInsert = false;
        }
    }
}

// Build the extracted content
$extractedContent = "-- Missing tables and data extracted from nazaarab_box.sql\n";
$extractedContent .= "-- Generated on " . date('Y-m-d H:i:s') . "\n\n";

foreach ($missingTables as $table) {
    if (isset($tableCreateContent) && strpos($tableCreateContent, "CREATE TABLE `$table`") !== false) {
        // Extract just this table's CREATE statement
        preg_match("/CREATE TABLE `$table`.*?;/s", $tableCreateContent, $matches);
        if (isset($matches[0])) {
            $extractedContent .= "-- Table structure for table `$table`\n";
            $extractedContent .= $matches[0] . "\n\n";
        }
    }
    
    if (isset($tableInsertContent[$table])) {
        $extractedContent .= "-- Data for table `$table`\n";
        $extractedContent .= $tableInsertContent[$table] . "\n\n";
    }
}

// Append to target file
echo "Appending extracted content to $targetFile...\n";

$targetHandle = fopen($targetFile, 'a');
if ($targetHandle === false) {
    die("Failed to open target file for writing: $targetFile\n");
}

fwrite($targetHandle, $extractedContent);
fclose($targetHandle);

echo "Successfully appended missing tables and data to $targetFile\n";

// Show statistics
$extractedLines = count(explode("\n", $extractedContent));
echo "Extracted $extractedLines lines of content\n";

echo "Done!\n";
?>
