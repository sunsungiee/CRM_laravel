

FROM php:8.0-apache

# Устанавливаем Node.js, npm и зависимости PHP
RUN apt-get update && \
    apt-get install -y nodejs npm && \
    npm install -g vite && \
    docker-php-ext-install pdo pdo_mysql

# Копируем проект
COPY . /var/www/html

# Устанавливаем зависимости
WORKDIR /var/www/html
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Настраиваем Apache
RUN chmod -R 777 storage bootstrap/cache
RUN php artisan optimize:clear

# Запускаем сервер
CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=10000 & npm run dev"]
