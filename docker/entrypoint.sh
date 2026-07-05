#!/bin/sh
set -e

echo "Starting container entrypoint"

# generate APP_KEY if not provided
if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "null" ]; then
  echo "Generating APP_KEY..."
  php artisan key:generate --force
fi

# wait for database to be available (simple retry loop)
MAX_ATTEMPTS=30
ATTEMPT=0
until php artisan migrate:status > /dev/null 2>&1 || [ $ATTEMPT -ge $MAX_ATTEMPTS ]; do
  ATTEMPT=$((ATTEMPT+1))
  echo "Waiting for database... (${ATTEMPT}/${MAX_ATTEMPTS})"
  sleep 3
done

if [ $ATTEMPT -ge $MAX_ATTEMPTS ]; then
  echo "Database not available after ${MAX_ATTEMPTS} attempts, continuing and letting artisan commands fail if necessary."
fi

echo "Running migrations and seeders"
php artisan migrate --force --seed || true

echo "Starting php-fpm"
exec php-fpm
