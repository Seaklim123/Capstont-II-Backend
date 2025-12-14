#!/bin/bash

set -e

echo "Starting application..."

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
    echo "Please check your database configuration"
    # Continue anyway for debugging
fi

echo "Database connection established!"

# Run migrations
echo "Running database migrations..."
php artisan migrate --force || {
    echo "Migration failed, but continuing..."
}

# Clear and cache configuration
echo "Optimizing application..."
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link if it doesn't exist
if [ ! -L /var/www/public/storage ]; then
    echo "Creating storage link..."
    php artisan storage:link || echo "Storage link already exists or failed"
fi

echo "Application setup complete!"

# Start supervisor
exec /usr/bin/supervisord -n -c /etc/supervisor/conf.d/supervisord.conf
