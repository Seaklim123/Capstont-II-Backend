# Use PHP 8.2 FPM (no Apache)
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies including PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    postgresql-client \
    zip \
    unzip \
    libzip-dev \
    nginx \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Create nginx config
RUN echo 'server {\n\
    listen 80;\n\
    index index.php;\n\
    error_log  /var/log/nginx/error.log;\n\
    access_log /var/log/nginx/access.log;\n\
    root /var/www/html/public;\n\
\n\
    location ~ \.php$ {\n\
        try_files $uri =404;\n\
        fastcgi_split_path_info ^(.+\.php)(/.+)$;\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        include fastcgi_params;\n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
        fastcgi_param PATH_INFO $fastcgi_path_info;\n\
    }\n\
\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
}\n\
' > /etc/nginx/sites-available/default

# Expose port 80
EXPOSE 80

# Simple start script with nginx + php-fpm
RUN echo '#!/bin/bash\n\
# Generate app key if not exists\n\
if [ -z "$APP_KEY" ]; then\n\
    php artisan key:generate --force\n\
fi\n\
\n\
# Clear and cache config\n\
php artisan config:clear || true\n\
php artisan config:cache || true\n\
\n\
# Run migrations (ignore errors)\n\
php artisan migrate --force || true\n\
\n\
# Start PHP-FPM in background\n\
php-fpm -D\n\
\n\
# Start Nginx\n\
nginx -g "daemon off;"\n\
' > /start.sh && chmod +x /start.sh

CMD ["/start.sh"]