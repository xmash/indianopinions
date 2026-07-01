#!/bin/sh
set -e

cd /app

echo "==> Indian Opinions boot (/entrypoint.sh)"

# Railway sets RAILWAY_PUBLIC_DOMAIN when APP_URL is not set.
if [ -n "$RAILWAY_PUBLIC_DOMAIN" ] && [ -z "$APP_URL" ]; then
    export APP_URL="https://${RAILWAY_PUBLIC_DOMAIN}"
    echo "==> APP_URL set from Railway: ${APP_URL}"
fi

if printf '%s' "${APP_URL:-}" | grep -qE '\$\{\{|\$\{'; then
    echo "ERROR: APP_URL looks like an unexpanded Railway template: ${APP_URL}"
    echo "  Set APP_URL to a literal URL, e.g. https://indianopinions.com"
    exit 1
fi

if [ -n "${APP_URL:-}" ]; then
    echo "==> APP_URL: ${APP_URL}"
fi

# Laravel reads DB_URL; Railway injects DATABASE_URL.
if [ -n "$DATABASE_URL" ] && [ -z "$DB_URL" ]; then
    export DB_URL="$DATABASE_URL"
fi

if [ -z "$APP_KEY" ]; then
    echo "ERROR: APP_KEY is not set."
    echo "  Railway → web service → Variables → add APP_KEY"
    echo "  Generate locally: php artisan key:generate --show"
    exit 1
fi

echo "==> DB config: ${DATABASE_URL:+DATABASE_URL set}${DATABASE_URL:-host=${DB_HOST:-?} port=${DB_PORT:-5432} db=${DB_DATABASE:-?}}"

# Railway sets CACHE_STORE=database — use file drivers until migrations create tables.
SAVED_CACHE_STORE="${CACHE_STORE:-database}"
SAVED_SESSION_DRIVER="${SESSION_DRIVER:-database}"
export CACHE_STORE=file
export SESSION_DRIVER=file

# ── Ensure storage directories exist ─────────────────────────
mkdir -p storage/framework/views \
         storage/framework/cache/data \
         storage/framework/sessions \
         storage/logs \
         storage/app/public/gallery \
         storage/app/public/videos \
         bootstrap/cache
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

rm -f bootstrap/cache/config.php bootstrap/cache/routes-v7.php 2>/dev/null || true

# ── Wait for PostgreSQL (max 60s) ─────────────────────────────
echo "==> Waiting for PostgreSQL..."
TRIES=0
until php docker/wait-for-postgres.php 2>/tmp/pg_error; do
    TRIES=$((TRIES + 1))
    echo "  attempt $TRIES: $(cat /tmp/pg_error 2>/dev/null | head -1)"
    if [ $TRIES -ge 30 ]; then
        echo "ERROR: PostgreSQL unreachable after 60s. Last error: $(cat /tmp/pg_error 2>/dev/null)"
        exit 1
    fi
    sleep 2
done
echo "  PostgreSQL is ready."

# ── Migrations (must run before any database cache/session use) ──
echo "==> Running migrations..."
php artisan migrate --force

php artisan storage:link --force 2>/dev/null || true

echo "==> Clearing caches (still using file drivers)..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# ── Restore production cache/session drivers ──────────────────
export CACHE_STORE="${SAVED_CACHE_STORE}"
export SESSION_DRIVER="${SAVED_SESSION_DRIVER}"

echo "==> Building config cache..."
php artisan config:cache || echo "WARNING: config:cache failed — continuing without cache"

# ── Assets check ─────────────────────────────────────────────
ls -la /app/public/build/assets/ 2>/dev/null || echo "WARNING: public/build/assets not found"

# ── Configure Nginx port ──────────────────────────────────────
echo "==> Starting on port ${PORT:-8080}..."
sed -i "s/listen 8080;/listen ${PORT:-8080};/" /etc/nginx/nginx.conf

php-fpm -D
exec nginx -g 'daemon off;'
