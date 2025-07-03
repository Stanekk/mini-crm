FROM php:8.4-fpm

# Zainstaluj zależności potrzebne do kompilacji xdebug i innych rozszerzeń
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip

# Zainstaluj Xdebug przez PECL
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Dodaj konfigurację xdebug
COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
