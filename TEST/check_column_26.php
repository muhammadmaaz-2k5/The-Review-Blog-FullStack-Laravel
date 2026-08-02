<?php
$sqlFile = 'nazaarac_nc.sql';
$handle = fopen($sqlFile, "r");
$buffer = '';
$inInsert = false;
while (($line = fgets($handle)) !== false) {
    if (strpos($line, 'INSERT INTO `articles`') === 0) {
        $inInsert = true;
        continue;
    }
    if ($inInsert) {
        $buffer .= $line;
        if (substr(trim($line), -1) === ';') break;
    }
}
fclose($handle);

$len = strlen($buffer);
$inString = false;
$stringChar = '';
$inTuple = false;
$currentTuple = [];
$currentValue = '';
$escaped = false;

$seriesIds = [];

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
            if (count($currentTuple) >= 28) {
                $seriesId = trim($currentTuple[26], "'\"");
                if (!isset($seriesIds[$seriesId])) {
                    $seriesIds[$seriesId] = 0;
                }
                $seriesIds[$seriesId]++;
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

print_r($seriesIds);
