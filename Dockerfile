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

# Copy specific Laravel files and directories
COPY composer.json composer.lock package.json package-lock.json ./
COPY app/ ./app/
COPY config/ ./config/
COPY database/ ./database/
COPY routes/ ./routes/
COPY bootstrap/ ./bootstrap/
COPY public/ ./public/
COPY resources/ ./resources/
COPY storage/ ./storage/
COPY tests/ ./tests/
COPY artisan ./
COPY phpunit.xml ./
COPY postcss.config.js ./
COPY tailwind.config.js ./
COPY vite.config.js ./
COPY .env ./

# Debug: Check what was copied
RUN echo "=== DEBUG: Files copied to container ===" && \
    ls -la /var/www/ && \
    echo "=== Checking for Laravel files ===" && \
    test -f /var/www/composer.json && echo "composer.json: YES" || echo "composer.json: NO" && \
    test -f /var/www/package.json && echo "package.json: YES" || echo "package.json: NO" && \
    test -d /var/www/app && echo "app/: YES" || echo "app/: NO" && \
    test -d /var/www/config && echo "config/: YES" || echo "config/: NO"

# Set proper permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www

# Create entrypoint script for automatic setup
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
# Install dependencies if vendor directory is empty\n\
if [ ! -d "/var/www/vendor" ] || [ -z "$(ls -A /var/www/vendor)" ]; then\n\
  echo "=== INSTALLING DEPENDENCIES ==="\n\
  cd /var/www\n\
  echo "Current directory: $(pwd)"\n\
  echo "Files in directory:"\n\
  ls -la\n\
  if [ -f "composer.json" ]; then\n\
    composer install --no-interaction --no-dev --optimize-autoloader\n\
    npm install --production\n\
    npm run build\n\
    echo "Dependencies installed successfully!"\n\
  else\n\
    echo "ERROR: composer.json not found in /var/www"\n\
    echo "Available files:"\n\
    ls -la /var/www/\n\
    exit 1\n\
  fi\n\
else\n\
  echo "Dependencies already installed - skipping"\n\
fi\n\
\n\
# Check if this is the first run\n\
if ! php artisan migrate:status > /dev/null 2>&1; then\n\
  echo "=== FIRST RUN DETECTED ==="\n\
  echo "Running initial migrations..."\n\
  php artisan migrate --force\n\
  echo "Running initial seeders..."\n\
  php artisan db:seed --force\n\
  echo "First run completed successfully!"\n\
else\n\
  echo "=== CHECKING FOR UPDATES ==="\n\
  \n\
  # Check for pending migrations\n\
  PENDING_MIGRATIONS=$(php artisan migrate:status | grep -c "Pending" || echo "0")\n\
  if [ "$PENDING_MIGRATIONS" -gt 0 ]; then\n\
    echo "Found $PENDING_MIGRATIONS pending migrations - running..."\n\
    php artisan migrate --force\n\
    echo "Migrations completed!"\n\
  else\n\
    echo "No pending migrations - database is up to date"\n\
  fi\n\
  \n\
  # Check if seeders need to be run (only if tables are empty)\n\
  USER_COUNT=$(php artisan tinker --execute="echo App\\Models\\User::count();" 2>/dev/null || echo "0")\n\
  if [ "$USER_COUNT" -eq 0 ]; then\n\
    echo "No users found - running seeders..."\n\
    php artisan db:seed --force\n\
    echo "Seeders completed!"\n\
  else\n\
    echo "Data already exists - skipping seeders"\n\
  fi\n\
fi\n\
\n\
# Always optimize cache for production\n\
echo "=== OPTIMIZING CACHE ==="\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
echo "Cache optimized!"\n\
\n\
# Sync external reviews automatically (only if Google API key is configured)\n\
if [ ! -z "$GOOGLE_PLACES_API_KEY" ]; then\n\
  echo "=== SYNCING EXTERNAL REVIEWS ==="\n\
  php artisan reviews:sync-external --limit=10 --force\n\
  echo "External reviews synced!"\n\
else\n\
  echo "Google Places API key not configured - skipping external reviews sync"\n\
fi\n\
\n\
echo "=== APPLICATION READY ==="\n\
echo "Starting PHP-FPM..."\n\
exec php-fpm' > /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

# Copy cron job file
COPY docker/cron/reviews-sync /etc/cron.d/reviews-sync

# Set proper permissions for cron job
RUN chmod 0644 /etc/cron.d/reviews-sync

# Create log directories
RUN mkdir -p /var/log && touch /var/log/reviews-sync.log /var/log/cache-clear.log /var/log/cache-optimize.log

# Start cron service in background
RUN echo '#!/bin/bash\n\
# Start cron daemon\n\
service cron start\n\
\n\
# Start the main entrypoint\n\
exec /usr/local/bin/entrypoint.sh' > /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["/usr/local/bin/start.sh"]