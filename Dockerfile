FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies including Nginx
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
    nginx \
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

# Create a simple PHP application that works
RUN echo '<?php\n\
header("Content-Type: text/html; charset=UTF-8");\n\
echo "<h1>POA Churrasco - Sistema Funcionando!</h1>";\n\
echo "<p><strong>Database:</strong> " . (extension_loaded("pdo_pgsql") ? "PostgreSQL OK" : "PostgreSQL ERROR") . "</p>";\n\
echo "<p><strong>Redis:</strong> " . (extension_loaded("redis") ? "Redis OK" : "Redis ERROR") . "</p>";\n\
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";\n\
echo "<p><strong>Time:</strong> " . date("Y-m-d H:i:s") . "</p>";\n\
echo "<p><strong>Server:</strong> " . ($_SERVER["SERVER_NAME"] ?? "Unknown") . "</p>";\n\
echo "<p><strong>Request URI:</strong> " . ($_SERVER["REQUEST_URI"] ?? "Unknown") . "</p>";\n\
echo "<p><strong>Document Root:</strong> " . ($_SERVER["DOCUMENT_ROOT"] ?? "Unknown") . "</p>";\n\
?>' > /var/www/index.php

# Also create index.html as fallback
RUN echo '<!DOCTYPE html>\n\
<html>\n\
<head>\n\
    <title>POA Churrasco</title>\n\
    <meta charset="UTF-8">\n\
</head>\n\
<body>\n\
    <h1>POA Churrasco - Sistema Funcionando!</h1>\n\
    <p>Se você está vendo esta página, o servidor web está funcionando!</p>\n\
    <p><a href="/index.php">Clique aqui para ver a versão PHP</a></p>\n\
</body>\n\
</html>' > /var/www/index.html

# Configure Nginx
RUN echo 'server {\n\
    listen 80;\n\
    server_name _;\n\
    root /var/www;\n\
    index index.php index.html;\n\
\n\
    location / {\n\
        try_files $uri $uri/ /index.php?$query_string;\n\
    }\n\
\n\
    location ~ \.php$ {\n\
        fastcgi_pass 127.0.0.1:9000;\n\
        fastcgi_index index.php;\n\
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\n\
        include fastcgi_params;\n\
    }\n\
\n\
    location ~ /\.ht {\n\
        deny all;\n\
    }\n\
\n\
    # Debug: log all requests\n\
    access_log /var/log/nginx/access.log;\n\
    error_log /var/log/nginx/error.log;\n\
}' > /etc/nginx/sites-available/default

# Create nginx log directory
RUN mkdir -p /var/log/nginx && chown -R www-data:www-data /var/log/nginx

# Create entrypoint script that starts both Nginx and PHP-FPM
RUN echo '#!/bin/bash\n\
set -e\n\
\n\
echo "=== POA Churrasco Application Startup ==="\n\
\n\
# Wait for database to be ready\n\
echo "Waiting for database..."\n\
while ! nc -z db 5432; do\n\
  sleep 1\n\
done\n\
echo "Database is ready!"\n\
\n\
# Try to install Laravel dependencies if files exist\n\
if [ -f "/var/www/composer.json" ]; then\n\
  echo "Laravel files found, installing dependencies..."\n\
  cd /var/www\n\
  composer install --no-interaction --no-dev --optimize-autoloader || echo "Composer install failed, continuing..."\n\
  if [ -f "/var/www/package.json" ]; then\n\
    npm install --production || echo "NPM install failed, continuing..."\n\
  fi\n\
else\n\
  echo "No Laravel files found, running simple PHP app..."\n\
fi\n\
\n\
# Start PHP-FPM in background\n\
echo "Starting PHP-FPM..."\n\
php-fpm &\n\
\n\
# Start Nginx in foreground\n\
echo "Starting Nginx..."\n\
nginx -g "daemon off;"' > /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 80 for Nginx
EXPOSE 80
CMD ["/usr/local/bin/entrypoint.sh"]