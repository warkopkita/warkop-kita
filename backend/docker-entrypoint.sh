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
    echo "Menunggu koneksi MySQL di ${DB_HOST}..."
    until php -r "try { new PDO('mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: 3306) . ';dbname=' . getenv('DB_DATABASE'), getenv('DB_USERNAME'), getenv('DB_PASSWORD')); exit(0); } catch (Exception \$e) { exit(1); }" 2>/dev/null; do
        echo "Database belum siap, mencoba lagi dalam 2 detik..."
        sleep 2
    done
    echo "Database siap! Menjalankan migrasi dan seeder otomatis..."
    php artisan migrate --seed --force || true
fi

# Clear & optimize cache
php artisan optimize:clear || true

echo "Starting Apache on port ${PORT:-80}..."
exec apache2-foreground
