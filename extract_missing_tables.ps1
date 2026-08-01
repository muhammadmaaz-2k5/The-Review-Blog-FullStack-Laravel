# PowerShell script to extract missing tables from nazaarab_box.sql
$sourceFile = "nazaarab_box.sql"
$targetFile = "nazaarac_nc.sql"
$tempFile = "missing_tables_temp.sql"

# Tables missing from nazaarac_nc.sql
$missingTables = @(
    'ad_settings',
    'audio_languages', 
    'clips',
    'contents',
    'dramas',
    'drama_requests',
    'drama_rewarded_ads',
    'episodes',
    'fcm_tokens',
    'homepage_sections',
    'languages',
    'link_visits',
    'movie_cache',
    'movie_categories',
    'movie_comments',
    'movie_failed_jobs',
    'movie_migrations',
    'movie_pages',
    'movie_sessions',
    'movie_settings',
    'movie_users',
    'pages',
    'rewarded_ad_unlocks',
    'settings'
)

Write-Host "Starting extraction of missing tables..."

# Read source file
$lines = Get-Content $sourceFile
$totalLines = $lines.Count
Write-Host "Source file has $totalLines lines"

$extractedContent = @()
$currentTable = $null
$inCreateTable = $false
$inInsert = $false
$tableContent = @()

for ($i = 0; $i -lt $totalLines; $i++) {
    $line = $lines[$i]
    
    # Check for CREATE TABLE
    if ($line -match "^CREATE TABLE `([^`]+)`") {
        $currentTable = $matches[1]
        if ($missingTables -contains $currentTable) {
            $inCreateTable = $true
            $tableContent = @($line)
            Write-Host "Found CREATE TABLE for missing table: $currentTable"
        } else {
            $inCreateTable = $false
            $currentTable = $null
        }
    }
    # Continue collecting CREATE TABLE content
    elseif ($inCreateTable -and $currentTable) {
        $tableContent += $line
        if ($line -match ";") {
            # CREATE TABLE statement ended
            $extractedContent += $tableContent
            $inCreateTable = $false
        }
    }
    
    # Check for INSERT statements for missing tables
    if ($line -match "^INSERT INTO `([^`]+)`") {
        $insertTable = $matches[1]
        if ($missingTables -contains $insertTable) {
            $inInsert = $true
            $currentTable = $insertTable
            $tableContent = @($line)
        } else {
            $inInsert = $false
            $currentTable = $null
        }
    }
    # Continue collecting INSERT content
    elseif ($inInsert -and $currentTable) {
        $tableContent += $line
        if ($line -match ";") {
            # INSERT statement ended
            $extractedContent += $tableContent
            $inInsert = $false
        }
    }
}

# Write extracted content to temp file
$header = @(
    "",
    "",
    "-- Missing tables and data extracted from nazaarab_box.sql",
    "-- Generated on $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')",
    ""
)

$allContent = $header + $extractedContent
$allContent | Out-File -FilePath $tempFile -Encoding UTF8

Write-Host "Extracted $($extractedContent.Count) statements to $tempFile"

# Append to target file
Add-Content -Path $targetFile -Value (Get-Content $tempFile)

Write-Host "Successfully appended missing tables and data to $targetFile"

# Clean up
Remove-Item $tempFile -ErrorAction SilentlyContinue

Write-Host "Done!"
