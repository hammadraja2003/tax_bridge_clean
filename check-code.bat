@echo off
php artisan security:check
composer audit
vendor\bin\phpstan analyse --memory-limit=1G
vendor\bin\pint
pause
