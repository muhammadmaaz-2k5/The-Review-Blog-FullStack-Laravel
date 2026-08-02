#!/bin/bash

# Production Fix Script
# Run this script to fix common production issues

echo "🔧 Laravel Production Fix Script"
echo "================================="
echo ""

# Colors
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo "Step 1: Clearing all caches..."
php artisan optimize:clear
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear
echo -e "${GREEN}✅ All caches cleared${NC}"
echo ""

echo "Step 2: Checking APP_KEY..."
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    echo "Generating APP_KEY..."
    php artisan key:generate --force
    echo -e "${GREEN}✅ APP_KEY generated${NC}"
else
    echo -e "${GREEN}✅ APP_KEY already exists${NC}"
fi
echo ""

echo "Step 3: Clearing compiled views..."
if [ -d "storage/framework/views" ]; then
    find storage/framework/views -name "*.php" -type f -delete
    echo -e "${GREEN}✅ Compiled views cleared${NC}"
else
    echo -e "${YELLOW}⚠️  View cache directory not found${NC}"
fi
echo ""

echo "Step 4: Verifying view files..."
VIEW_COUNT=$(find resources/views -name "*.blade.php" -type f 2>/dev/null | wc -l)
if [ "$VIEW_COUNT" -gt 0 ]; then
    echo -e "${GREEN}✅ Found $VIEW_COUNT view files${NC}"
else
    echo -e "${RED}❌ No view files found!${NC}"
fi
echo ""

echo "Step 5: Checking storage permissions..."
chmod -R 755 storage bootstrap/cache 2>/dev/null
echo -e "${GREEN}✅ Storage permissions updated${NC}"
echo ""

echo "Step 6: Running final diagnostics..."
php artisan diagnose
echo ""

echo -e "${GREEN}✅ Production fix complete!${NC}"
echo ""
echo "If issues persist:"
echo "  1. Check file permissions: chmod -R 755 storage bootstrap/cache"
echo "  2. Verify .env file has correct settings"
echo "  3. Run: php artisan config:cache"
echo "  4. Run: php artisan route:cache"
echo "  5. Run: php artisan view:cache"

