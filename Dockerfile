FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libsqlite3-dev \
    libzip-dev \
    nodejs \
    npm \
    && docker-php-ext-install pdo pdo_sqlite zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

# Crear .env desde el ejemplo y generar APP_KEY
RUN cp .env.example .env

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN php artisan key:generate

RUN npm install && npm run build

RUN mkdir -p database \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/app/public \
    bootstrap/cache \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database

EXPOSE 8000

CMD php artisan storage:link || true && php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}