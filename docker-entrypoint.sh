#!/bin/bash
set -e

echo "Waiting DNS for MySQL ($DB_HOST)..."

#Clear and ready cache
composer cache-clear
composer cache-ready

#Opcache info
php -i | grep opcache.enable
php -i | grep opcache.memory_consumption
php -i | grep opcache.max_accelerated_files

#Executing php-fpm process
echo "Executing PHP-FPM..."
exec php-fpm
