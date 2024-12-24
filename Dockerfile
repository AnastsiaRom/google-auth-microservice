FROM php:8.2-fpm

# Установка зависимостей
RUN apt-get update && apt-get install -y \
  libpq-dev \
  git \
  zip \
  unzip

# Установка PostgreSQL PDO
RUN docker-php-ext-install pdo pdo_pgsql

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Установка рабочей директории
WORKDIR /var/www/html

# Копирование файлов проекта
COPY ./laravel /var/www/html

# Установка зависимостей Composer
RUN composer install --no-dev --optimize-autoloader

# Настройка прав доступа
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

CMD ["php-fpm"]
