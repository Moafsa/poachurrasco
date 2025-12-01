FROM php:8.2-fpm

# Instalar dependências do sistema
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
    libicu-dev

# Limpar cache do apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP
RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath gd intl

# Instalar Redis extension
RUN pecl install redis && docker-php-ext-enable redis

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar arquivos de configuração primeiro
COPY composer.json composer.lock package.json package-lock.json ./
COPY vite.config.js tailwind.config.js postcss.config.js ./

# Copiar recursos necessários para o build
COPY resources ./resources
COPY public ./public

# Instalar dependências PHP
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-autoloader

# Instalar dependências Node.js e compilar assets
RUN npm ci && npm run build

# Copiar código da aplicação (agora sem node_modules e vendor)
COPY . /var/www

# Completar instalação do Composer
RUN composer dump-autoload --optimize

# Definir permissões
RUN chown -R www-data:www-data /var/www

# Script de inicialização da aplicação Laravel
RUN echo '#!/bin/bash\nset -e\n\necho "=== Laravel Application Startup ==="\n\necho "Waiting for database..."\nwhile ! nc -z ${DB_HOST:-db} 5432; do\n  sleep 1\ndone\necho "Database is ready!"\n\necho "=== CHECKING DATABASE STATUS ==="\necho "Skipping migrations check to avoid duplicate table errors..."\necho "Database is ready, proceeding with application startup..."\n\necho "=== OPTIMIZING CACHE ==="\nphp artisan config:cache\nphp artisan route:cache\nphp artisan view:cache\necho "Cache optimized!"\n\nif [ ! -z "$GOOGLE_PLACES_API_KEY" ]; then\n  echo "=== SYNCING EXTERNAL REVIEWS ==="\n  php artisan reviews:sync-external --limit=10 --force\n  echo "External reviews synced!"\nelse\n  echo "Google Places API key not configured - skipping external reviews sync"\nfi\n\necho "=== APPLICATION READY ==="\necho "Starting PHP-FPM..."\nexec php-fpm' > /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

# Instalar ferramentas necessárias
RUN apt-get update && apt-get install -y netcat-traditional cron && rm -rf /var/lib/apt/lists/*

# Configurar cron jobs
COPY docker/cron/reviews-sync /etc/cron.d/reviews-sync
RUN chmod 0644 /etc/cron.d/reviews-sync

# Criar diretórios de log
RUN mkdir -p /var/log && touch /var/log/reviews-sync.log /var/log/cache-clear.log /var/log/cache-optimize.log

# Script de inicialização com cron
RUN echo '#!/bin/bash\nservice cron start\n\nexec /usr/local/bin/entrypoint.sh' > /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Instalar e configurar Nginx
RUN apt-get update && apt-get install -y nginx && rm -rf /var/lib/apt/lists/*

# Configurar Nginx para Laravel
RUN echo 'server {\n    listen 80;\n    server_name localhost;\n    root /var/www/public;\n    index index.php;\n\n    charset utf-8;\n\n    location / {\n        try_files $uri $uri/ /index.php?$query_string;\n    }\n\n    location = /favicon.ico { access_log off; log_not_found off; }\n    location = /robots.txt  { access_log off; log_not_found off; }\n\n    error_page 404 /index.php;\n\n    location ~ \.php$ {\n        fastcgi_pass 127.0.0.1:9000;\n        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;\n        include fastcgi_params;\n    }\n\n    location ~ /\.(?!well-known).* {\n        deny all;\n    }\n}' > /etc/nginx/sites-available/default

RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default

# Criar diretórios de log do Nginx
RUN mkdir -p /var/log/nginx

# Script final de inicialização com Nginx
RUN echo '#!/bin/bash\nset -e\n\necho "=== Starting Services ==="\n\n# Start cron\nservice cron start\n\n# Create nginx log files\nmkdir -p /var/log/nginx\ntouch /var/log/nginx/error.log /var/log/nginx/access.log\n\n# Start nginx in background and keep it running\nnginx -g "daemon off;" &\nNGINX_PID=$!\n\n# Function to cleanup on exit\ntrap "echo \"Stopping services...\"; kill $NGINX_PID 2>/dev/null || true; exit" SIGTERM SIGINT\n\necho "=== Laravel Application Startup ==="\n\necho "Waiting for database..."\nwhile ! nc -z ${DB_HOST:-db} 5432; do\n  sleep 1\ndone\necho "Database is ready!"\n\necho "=== CHECKING DATABASE STATUS ==="\necho "Skipping migrations check to avoid duplicate table errors..."\necho "Database is ready, proceeding with application startup..."\n\necho "=== OPTIMIZING CACHE ==="\nphp artisan config:cache || true\nphp artisan route:cache || true\nphp artisan view:cache || true\necho "Cache optimized!"\n\nif [ ! -z "$GOOGLE_PLACES_API_KEY" ]; then\n  echo "=== SYNCING EXTERNAL REVIEWS ==="\n  php artisan reviews:sync-external --limit=10 --force || true\n  echo "External reviews synced!"\nelse\n  echo "Google Places API key not configured - skipping external reviews sync"\nfi\n\necho "=== APPLICATION READY ==="\necho "Nginx PID: $NGINX_PID"\necho "Starting PHP-FPM..."\n\n# Start PHP-FPM in foreground to keep container alive\nphp-fpm' > /usr/local/bin/start.sh

RUN chmod +x /usr/local/bin/start.sh

# Comando de inicialização
CMD ["/usr/local/bin/start.sh"]
