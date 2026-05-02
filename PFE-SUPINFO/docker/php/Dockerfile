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

WORKDIR /var/www/html

# Permissions (à adapter si besoin)
RUN chown -R www-data:www-data /var/www/html
