#!/bin/bash
# Script to clear Laravel caches
# Run this on your server: bash clear_cache.sh

echo "=== Clearing Laravel Caches ==="
echo ""

# Clear route cache
echo "Clearing route cache..."
php artisan route:clear
echo ""

# Clear application cache
echo "Clearing application cache..."
php artisan cache:clear
echo ""

# Clear config cache
echo "Clearing config cache..."
php artisan config:clear
echo ""

# Clear all caches
echo "Clearing all caches..."
php artisan optimize:clear
echo ""

# Try to remove route cache file directly if it still exists
if [ -f "bootstrap/cache/routes-v7.php" ]; then
    echo "Removing route cache file directly..."
    rm -f bootstrap/cache/routes-v7.php
    echo "✅ Route cache file removed"
else
    echo "✅ No route cache file found"
fi

echo ""
echo "=== Verification ==="
echo "Checking if route is now registered..."
php artisan route:list --name=without-download-links

echo ""
echo "=== Complete ==="

