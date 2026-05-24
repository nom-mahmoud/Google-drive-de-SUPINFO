FROM php:8.4-fpm-alpine

# Installation des dépendances système
RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    oniguruma-dev

# Installation des extensions PHP
RUN docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Increase PHP upload and memory limits
RUN echo "upload_max_filesize = 100M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size = 100M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html

# Permissions (à adapter si besoin)
RUN chown -R www-data:www-data /var/www/html
