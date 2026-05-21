#!/usr/bin/env sh
set -e

cd /var/www

if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

if [ ! -f vendor/autoload.php ]; then
    echo "Installing Composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

if [ -f artisan ]; then
    if [ ! -f database/database.sqlite ] && grep -q "^DB_CONNECTION=sqlite" .env 2>/dev/null; then
        touch database/database.sqlite
    fi

    if grep -q "^APP_KEY=$" .env 2>/dev/null; then
        php artisan key:generate --force
    fi
fi

exec "$@"
