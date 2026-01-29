FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    git unzip zip libzip-dev libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# PHP memory for phpstan, etc.
RUN echo "memory_limit=512M" > /usr/local/etc/php/conf.d/memory-limit.ini

WORKDIR /var/www/html
