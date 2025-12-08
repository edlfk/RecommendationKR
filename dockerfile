FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git zip unzip libpq-dev \
  && docker-php-ext-install pdo pdo_pgsql

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock* /var/www/html/
RUN composer install --no-interaction --no-scripts --prefer-dist || true

