<?php
$sqlFile = 'nazaarac_nc.sql';
$content = file_get_contents($sqlFile);

$pattern = '/CREATE TABLE `articles` \((.*?)\) ENGINE=/s';
if (preg_match($pattern, $content, $matches)) {
    echo "CREATE TABLE `articles` (\n" . $matches[1] . "\n) ENGINE=\n";
} else {
    echo "CREATE TABLE `articles` not found.\n";
}
