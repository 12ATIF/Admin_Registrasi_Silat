#!/usr/bin/env bash
set -e

echo "Waiting for database to be ready..."
while ! php -r "try { new PDO('pgsql:host='.getenv('DB_HOST').';dbname='.getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (Exception \$e) { exit(1); }"; do
  echo "Database is unavailable - sleeping"
  sleep 2
done
echo "Database is ready!"

echo "Running migrations..."
php artisan migrate --force

echo "Caching config, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Linking storage..."
php artisan storage:link || true

echo "Starting original command..."
exec "$@"
