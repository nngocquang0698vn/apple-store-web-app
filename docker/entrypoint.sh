#!/bin/bash
set -e

cd /var/www/html

if [ ! -f .env ]; then
    if [ -f .env.docker.example ]; then
        cp .env.docker.example .env
        echo "Created .env from .env.docker.example"
    elif [ -f .env.example ]; then
        cp .env.example .env
        echo "Created .env from .env.example"
    fi
fi

DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-apple_store}"
DB_USERNAME="${DB_USERNAME:-apple_store}"
DB_PASSWORD="${DB_PASSWORD:-secret}"

echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT}..."
until php -r "
    try {
        new PDO(
            'mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}',
            '${DB_USERNAME}',
            '${DB_PASSWORD}',
            [PDO::ATTR_TIMEOUT => 3]
        );
        exit(0);
    } catch (Throwable \$e) {
        exit(1);
    }
" 2>/dev/null; do
    sleep 2
done
echo "MySQL is ready."

if [ -f .env ] && ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force --no-interaction
    echo "Generated APP_KEY."
fi

php artisan storage:link --force --no-interaction 2>/dev/null || true

chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

exec apache2-foreground
