FROM php:8.2-fpm

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip

RUN install-php-extensions \
    intl \
    opcache \
    pdo_mysql \
    zip \
    apcu \
    gd \
    xml \
    dom \
    sysvsem

WORKDIR /var/www/website

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
