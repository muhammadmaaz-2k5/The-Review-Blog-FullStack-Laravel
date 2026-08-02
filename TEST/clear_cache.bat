@echo off
REM Script to clear Laravel caches (Windows version)
REM Note: This is for reference - run the commands on your server (Linux)

echo === Clearing Laravel Caches ===
echo.
echo Please run these commands on your server:
echo.
echo php artisan route:clear
echo php artisan cache:clear
echo php artisan config:clear
echo php artisan optimize:clear
echo del bootstrap\cache\routes-v7.php
echo php artisan route:list ^| findstr without-download-links
echo.
pause

