#!/bin/bash

# Wait for database to be ready (with timeout)
echo "Waiting for database connection..."
timeout=60
counter=0

until pg_isready -h "$DB_HOST" -p "$DB_PORT" -U "$DB_USERNAME" || [ $counter -eq $timeout ]; do
    echo "Database is unavailable - sleeping ($counter/$timeout)"
    sleep 2
    counter=$((counter + 1))
done

if [ $counter -eq $timeout ]; then
    echo "Database connection timeout. Continuing anyway..."
fi

echo "Database check complete - executing commands"

# Generate app key if not exists
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Clear and cache config
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
echo "Running database migrations..."
php artisan migrate --force || echo "Migration failed, continuing..."

# Create symbolic link for storage
if [ ! -L /var/www/html/public/storage ]; then
    echo "Creating storage link..."
    php artisan storage:link
fi

# Start Apache
echo "Starting Apache on port 80..."
exec "$@"