<?php
$sqlFile = 'nazaarac_nc.sql';
$handle = fopen($sqlFile, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        if (strpos($line, 'INSERT INTO `articles`') !== false) {
            echo "Found insert statement:\n";
            echo substr($line, 0, 500) . "...\n";
            break;
        }
    }
    fclose($handle);
} else {
    echo "Error opening file.";
}
