#!/bin/bash
set -e

echo "Caching Laravel..."

php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Running migration..."

php artisan migrate --force || true

echo "Starting PHP-FPM..."

php-fpm -D

echo "Starting Nginx..."

exec nginx -g "daemon off;"