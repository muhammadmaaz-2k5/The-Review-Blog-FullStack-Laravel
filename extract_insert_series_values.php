<?php
$sqlFile = 'nazaarac_nc.sql';
$handle = fopen($sqlFile, "r");
if ($handle) {
    $found = false;
    $count = 0;
    while (($line = fgets($handle)) !== false) {
        if (strpos($line, 'INSERT INTO `article_series`') !== false) {
            $found = true;
            echo "Found article_series insert statement:\n";
            continue;
        }
        if ($found) {
            echo $line;
            $count++;
            if ($count >= 5) break;
        }
    }
    fclose($handle);
} else {
    echo "Error opening file.";
}
