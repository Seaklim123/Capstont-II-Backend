#!/bin/bash

set -e

echo "Starting application..."

# Fix permissions first
echo "Setting up permissions..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache
touch /var/www/storage/logs/laravel.log
chown www-data:www-data /var/www/storage/logs/laravel.log

# Check if we have database environment variables
if [ -z "$DB_HOST" ]; then
    echo "WARNING: DB_HOST is not set!"
    echo "Skipping database connection check..."
else
    echo "Database Host: $DB_HOST"
    echo "Database Port: $DB_PORT"
    echo "Database Name: $DB_DATABASE"

    # Wait for database to be ready
    echo "Waiting for database connection..."
    max_retries=30
    retry_count=0

    until php artisan db:show > /dev/null 2>&1 || [ $retry_count -eq $max_retries ]; do
        echo "Database not ready yet... Attempt $((retry_count + 1))/$max_retries"
        retry_count=$((retry_count + 1))
        sleep 2
    done

    if [ $retry_count -eq $max_retries ]; then
        echo "ERROR: Could not connect to database after $max_retries attempts"
        echo "Please verify your database configuration in Render.com"
        echo "Continuing anyway to allow debugging..."
    else
        echo "Database connection established!"

        # Run migrations
        echo "Running database migrations..."
        php artisan migrate --force || {
            echo "Migration failed. Check database credentials."
        }
    fi
fi

# Clear and cache configuration
echo "Optimizing application..."
php artisan config:clear
php artisan config:cache || echo "Config cache failed"
php artisan route:cache || echo "Route cache failed"
php artisan view:cache || echo "View cache failed"

# Create storage link if it doesn't exist
if [ ! -L /var/www/public/storage ]; then
    echo "Creating storage link..."
    php artisan storage:link || echo "Storage link already exists or failed"
fi

echo "Application setup complete!"
echo "Starting services..."

# Start supervisor
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
