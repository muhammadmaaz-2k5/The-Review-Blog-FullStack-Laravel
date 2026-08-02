<?php
$sqlFile = 'nazaarac_nc.sql';
$handle = fopen($sqlFile, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        if (strpos($line, 'INSERT INTO `articles`') !== false) {
            // Read the next line which has the values
            $valuesLine = fgets($handle);
            // It looks like: (1, 'Jim Curtis...', ..., NULL, NULL),
            // Let's just use str_getcsv but it uses comma delimiter
            // We need to parse a SQL insert row
            preg_match('/^\((.*)\),?$/', trim($valuesLine), $matches);
            if (isset($matches[1])) {
                $row = $matches[1];
                // basic CSV parsing won't perfectly work for SQL if there are escaped quotes, but let's try
                $elements = str_getcsv($row, ',', "'", "\\");
                foreach ($elements as $index => $value) {
                    echo "[$index] => " . substr($value, 0, 30) . "\n";
                }
            }
            break;
        }
    }
    fclose($handle);
} else {
    echo "Error opening file.";
}
