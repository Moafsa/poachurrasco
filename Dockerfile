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

# Create a minimal Laravel application structure
RUN echo '{"name":"laravel/laravel","type":"project","description":"The skeleton application for the Laravel framework.","keywords":["laravel","framework"],"license":"MIT","require":{"php":"^8.2","laravel/framework":"^11.0","laravel/socialite":"^5.23","laravel/tinker":"^2.10.1"},"require-dev":{"fakerphp/faker":"^1.23","laravel/pail":"^1.2.2","laravel/pint":"^1.24","laravel/sail":"^1.41","mockery/mockery":"^1.6","nunomaduro/collision":"^8.6","phpunit/phpunit":"^11.5.3"},"autoload":{"psr-4":{"App\\\\":"app/","Database\\\\Factories\\\\":"database/factories/","Database\\\\Seeders\\\\":"database/seeders/"}},"autoload-dev":{"psr-4":{"Tests\\\\":"tests/"}},"scripts":{"post-autoload-dump":["Illuminate\\\\Foundation\\\\ComposerScripts::postAutoloadDump","@php artisan package:discover --ansi"],"post-update-cmd":["@php artisan vendor:publish --tag=laravel-assets --ansi --force"],"post-root-package-install":["@php -r \\"file_exists(.env) || copy(.env.example, .env);\\""],"post-create-project-cmd":["@php artisan key:generate --ansi","@php -r \\"file_exists(database/database.sqlite) || touch(database/database.sqlite);\\"","@php artisan migrate --graceful --ansi"],"pre-package-uninstall":["Illuminate\\\\Foundation\\\\ComposerScripts::prePackageUninstall"]},"extra":{"laravel":{"dont-discover":[]}},"config":{"optimize-autoloader":true,"preferred-install":"dist","sort-packages":true,"allow-plugins":{"pestphp/pest-plugin":true,"php-http/discovery":true}},"minimum-stability":"stable","prefer-stable":true}' > composer.json

RUN echo '{"name":"laravel/laravel","type":"project","description":"The skeleton application for the Laravel framework.","keywords":["laravel","framework"],"license":"MIT","require":{"php":"^8.2","laravel/framework":"^11.0","laravel/socialite":"^5.23","laravel/tinker":"^2.10.1"},"require-dev":{"fakerphp/faker":"^1.23","laravel/pail":"^1.2.2","laravel/pint":"^1.24","laravel/sail":"^1.41","mockery/mockery":"^1.6","nunomaduro/collision":"^8.6","phpunit/phpunit":"^11.5.3"},"autoload":{"psr-4":{"App\\\\":"app/","Database\\\\Factories\\\\":"database/factories/","Database\\\\Seeders\\\\":"database/seeders/"}},"autoload-dev":{"psr-4":{"Tests\\\\":"tests/"}},"scripts":{"post-autoload-dump":["Illuminate\\\\Foundation\\\\ComposerScripts::postAutoloadDump","@php artisan package:discover --ansi"],"post-update-cmd":["@php artisan vendor:publish --tag=laravel-assets --ansi --force"],"post-root-package-install":["@php -r \\"file_exists(.env) || copy(.env.example, .env);\\""],"post-create-project-cmd":["@php artisan key:generate --ansi","@php -r \\"file_exists(database/database.sqlite) || touch(database/database.sqlite);\\"","@php artisan migrate --graceful --ansi"],"pre-package-uninstall":["Illuminate\\\\Foundation\\\\ComposerScripts::prePackageUninstall"]},"extra":{"laravel":{"dont-discover":[]}},"config":{"optimize-autoloader":true,"preferred-install":"dist","sort-packages":true,"allow-plugins":{"pestphp/pest-plugin":true,"php-http/discovery":true}},"minimum-stability":"stable","prefer-stable":true}' > composer.lock

RUN echo '{"name":"laravel/laravel","private":true,"type":"module","scripts":{"build":"vite build","dev":"vite"},"devDependencies":{"axios":"^1.7.4","laravel-vite-plugin":"^1.0.0","vite":"^6.0.0"}}' > package.json

# Create basic Laravel structure
RUN mkdir -p app/Http/Controllers app/Models database/migrations database/seeders config routes bootstrap/cache public resources/views storage/logs

# Create basic artisan file
RUN echo '#!/usr/bin/env php\n<?php\n\ndefine("LARAVEL_START", microtime(true));\n\nrequire __DIR__."/vendor/autoload.php";\n\n$app = require_once __DIR__."/bootstrap/app.php";\n\n$kernel = $app->make(Illuminate\\Contracts\\Console\\Kernel::class);\n\n$status = $kernel->handle(\n    $input = new Symfony\\Component\\Console\\Input\\ArgvInput,\n    new Symfony\\Component\\Console\\Output\\ConsoleOutput\n);\n\n$kernel->terminate($input, $status);\n\nexit($status);' > artisan

RUN chmod +x artisan

# Set proper permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www

# Install dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader
RUN npm install --production

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
echo "=== APPLICATION READY ==="\n\
echo "Starting PHP-FPM..."\n\
exec php-fpm' > /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["/usr/local/bin/entrypoint.sh"]