<?php
$env = parse_ini_file('.env');
$mysqli = new mysqli($env['DB_HOST'], $env['DB_USERNAME'], $env['DB_PASSWORD'], $env['DB_DATABASE'], $env['DB_PORT']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "Connected successfully\n";
$sql = file_get_contents('temp_articles.sql');
echo "Loaded SQL, executing...\n";

if ($mysqli->multi_query($sql)) {
    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());
    echo "SQL executed successfully.\n";
} else {
    echo "Error executing SQL: " . $mysqli->error . "\n";
}

echo "Now updating articles...\n";
$updateSql = "UPDATE articles a JOIN temp_articles ta ON a.id = ta.id SET a.series_id = ta.series_id, a.series_order = ta.series_order";
if ($mysqli->query($updateSql) === TRUE) {
    echo "Update successful! Rows affected: " . $mysqli->affected_rows . "\n";
} else {
    echo "Update error: " . $mysqli->error . "\n";
}

$mysqli->query("DROP TABLE temp_articles");
$mysqli->close();
echo "Done.\n";
