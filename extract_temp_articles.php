<?php
$sourceFile = 'nazaarac_nc.sql';
$targetFile = 'temp_articles.sql';
$sourceHandle = fopen($sourceFile, 'r');
$targetHandle = fopen($targetFile, 'w');

fwrite($targetHandle, "DROP TABLE IF EXISTS `temp_articles`;\n");

$inArticlesCreate = false;
$inArticlesInsert = false;

while (($line = fgets($sourceHandle)) !== false) {
    if (strpos($line, 'CREATE TABLE `articles`') === 0) {
        $inArticlesCreate = true;
        fwrite($targetHandle, str_replace('`articles`', '`temp_articles`', $line));
        continue;
    }
    
    if ($inArticlesCreate) {
        fwrite($targetHandle, $line);
        if (strpos($line, ') ENGINE=') === 0) {
            $inArticlesCreate = false;
        }
        continue;
    }
    
    if (strpos($line, 'INSERT INTO `articles`') === 0) {
        $inArticlesInsert = true;
        fwrite($targetHandle, str_replace('`articles`', '`temp_articles`', $line));
        continue;
    }
    
    if ($inArticlesInsert) {
        fwrite($targetHandle, $line);
        // The insert statement ends with a semicolon
        if (substr(trim($line), -1) === ';') {
            $inArticlesInsert = false;
        }
        continue;
    }
}

fclose($sourceHandle);
fclose($targetHandle);
echo "temp_articles.sql generated.\n";
