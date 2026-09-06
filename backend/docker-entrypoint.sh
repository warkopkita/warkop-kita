#!/bin/bash
set -e

# Support dynamic PORT environment variable (Railway/Render)
if [ -n "$PORT" ]; then
    sed -ri -e "s!Listen 80!Listen ${PORT}!g" /etc/apache2/ports.conf
    sed -ri -e "s!<VirtualHost \*:80>!<VirtualHost \*:${PORT}>!g" /etc/apache2/sites-available/*.conf
fi

# Ensure storage directories exist and have proper permissions
mkdir -p /var/www/html/storage/framework/{sessions,views,cache} /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Run storage:link if not already linked
php artisan storage:link 2>/dev/null || true

# Wait for database & run migrations if DB is set
if [ -n "$DB_HOST" ]; then
    echo "Running migrations..."
    php artisan migrate --force || echo "Migration failed or database not ready yet."
fi

# Optimize cache
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Starting Apache on port ${PORT:-80}..."
exec apache2-foreground
