FROM php:8.0.2-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql opcache exif

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
