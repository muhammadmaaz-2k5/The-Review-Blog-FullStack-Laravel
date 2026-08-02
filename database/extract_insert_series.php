<?php
$sqlFile = 'nazaarac_nc.sql';
$handle = fopen($sqlFile, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        if (strpos($line, 'INSERT INTO `article_series`') !== false) {
            echo "Found article_series insert statement:\n";
            echo substr($line, 0, 1000) . "...\n";
            break;
        }
    }
    fclose($handle);
} else {
    echo "Error opening file.";
}
