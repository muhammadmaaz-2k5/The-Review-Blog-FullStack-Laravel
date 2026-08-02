<?php
$sqlFile = 'nazaarac_nc.sql';
$handle = fopen($sqlFile, "r");
if ($handle) {
    $found = false;
    $count = 0;
    while (($line = fgets($handle)) !== false) {
        if (strpos($line, 'INSERT INTO `articles`') !== false) {
            $found = true;
            echo "Found articles insert statement:\n";
            continue;
        }
        if ($found) {
            echo substr($line, 0, 1000) . "...\n";
            $count++;
            if ($count >= 3) break;
        }
    }
    fclose($handle);
} else {
    echo "Error opening file.";
}
