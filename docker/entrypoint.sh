#!/bin/bash

# Wait for database to be ready
echo "Waiting for database connection..."
while ! mysql -h db -u"${DB_USERNAME}" -p"${DB_PASSWORD}" -e "SELECT 1" >/dev/null 2>&1; do
    sleep 1
done

# Run migrations
php artisan migrate --force

# Run seeders
php artisan db:seed --force

# Ensure log directory exists and has correct permissions
mkdir -p /var/www/storage/logs
touch /var/www/storage/logs/scheduler.log
chown -R www-data:www-data /var/www/storage/logs

# Start supervisor
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf 