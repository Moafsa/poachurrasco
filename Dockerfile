FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libpq-dev \
    nodejs \
    npm \
    netcat-traditional \
    cron \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd

# Install Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . /var/www

# Set proper permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www

# Install dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader || true
RUN npm install --production || true

# Create entrypoint script
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "=== Laravel Application Startup ==="\n\
\n\
# Wait for database to be ready\n\
echo "Waiting for database..."\n\
while ! nc -z db 5432; do\n\
  sleep 1\n\
done\n\
echo "Database is ready!"\n\
\n\
# Try to install dependencies if they failed during build\n\
if [ ! -d "/var/www/vendor" ] || [ -z "$(ls -A /var/www/vendor)" ]; then\n\
  echo "Installing dependencies at runtime..."\n\
  cd /var/www\n\
  composer install --no-interaction --no-dev --optimize-autoloader || echo "Composer install failed, continuing..."\n\
  npm install --production || echo "NPM install failed, continuing..."\n\
fi\n\
\n\
echo "=== APPLICATION READY ==="\n\
echo "Starting PHP-FPM..."\n\
exec php-fpm' > /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["/usr/local/bin/entrypoint.sh"]