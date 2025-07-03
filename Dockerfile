FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    curl \
    && docker-php-ext-install zip

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY ./xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

