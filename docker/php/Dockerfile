FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libonig-dev libxml2-dev libzip-dev gnupg netcat-openbsd \
    && curl -fsSL https://deb.nodesource.com/setup_18.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./docker/php/custom-php.ini /usr/local/etc/php/conf.d/


WORKDIR /var/www
COPY . .

COPY ./entrypoint.sh /var/www/entrypoint.sh
RUN chmod +x /var/www/entrypoint.sh
