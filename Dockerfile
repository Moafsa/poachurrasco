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

# Copy composer files first for better caching
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy package files for npm
COPY package.json package-lock.json ./

# Install Node dependencies
RUN npm ci --only=production

# Copy application code
COPY . /var/www

# Build assets
RUN npm run build

# Change ownership
RUN chown -R www-data:www-data /var/www

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

# Install netcat and cron for database connectivity check and scheduled tasks
RUN apt-get update && apt-get install -y netcat-traditional cron && rm -rf /var/lib/apt/lists/*

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
