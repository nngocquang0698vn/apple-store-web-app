#!/bin/bash
set -e

cd /var/www/html

if [ -f .env ] && ! grep -qE '^APP_KEY=base64:.+' .env; then
    php artisan key:generate --force --no-interaction
fi

php artisan storage:link --force --no-interaction 2>/dev/null || true

# Build cache từ .env hiện tại (tránh APP_KEY rỗng bị bake vào config cache)
php artisan optimize:clear --no-interaction
php artisan optimize --no-interaction

chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rwx storage bootstrap/cache 2>/dev/null || true

exec apache2-foreground
