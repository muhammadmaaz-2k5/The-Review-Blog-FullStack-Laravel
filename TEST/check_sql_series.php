<?php
$sqlFile = 'nazaarac_nc.sql';
$handle = fopen($sqlFile, "r");
$found = 0;
while (($line = fgets($handle)) !== false) {
    if (strpos($line, 'INSERT INTO `articles`') === 0) {
        $buffer = $line;
        while (($line = fgets($handle)) !== false) {
            $buffer .= $line;
            if (substr(trim($line), -1) === ';') break;
        }
        
        // Find tuples: `(1211, ...)`
        // Since we know series_id is near the end, let's just find `\d+, \d+, 'published'` ?
        // Actually, let's just see if there's any non-NULL in column 26
        // Let's print out the first 5 matches of `\d+, \d+, 'published'`
        preg_match_all('/(\d+),\s*(\d+),\s*(\d+),\s*\'(published|draft)\'/', $buffer, $matches);
        
        echo "Found " . count($matches[0]) . " potential series_id matches.\n";
        for ($i = 0; $i < min(5, count($matches[0])); $i++) {
            echo $matches[0][$i] . "\n";
        }
        
        // Wait, the structure from my previous test:
        // [25] => category_id (e.g. 9)
        // [26] => series_id (e.g. NULL)
        // [27] => series_order (e.g. NULL)
        // [28] => view_count (e.g. 2)
        // [29] => status (e.g. published)
        break;
    }
}
fclose($handle);
