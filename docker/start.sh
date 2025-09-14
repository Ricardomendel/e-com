#!/usr/bin/env bash
set -euo pipefail

cd /var/www

# Install dependencies (prefer dist); generate app key if missing
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

if [ ! -f .env ]; then
  cp .env.example .env || true
fi

php artisan key:generate --force

# Storage link
php artisan storage:link || true

# Optimize
php artisan optimize:clear || true

# Ensure SQLite database file exists if using sqlite
if [ "${DB_CONNECTION:-}" = "sqlite" ]; then
  if [ -z "${DB_DATABASE:-}" ]; then
    export DB_DATABASE="/var/www/storage/database.sqlite"
  fi
  mkdir -p "$(dirname "$DB_DATABASE")"
  if [ ! -f "$DB_DATABASE" ]; then
    touch "$DB_DATABASE"
  fi
fi

# Publish Filament assets if route exists (safe no-op otherwise)
php artisan filament:upgrade || true

# Migrate and seed if enabled
if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  php artisan migrate --force
fi

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf


