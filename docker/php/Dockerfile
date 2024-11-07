FROM php:8.2-fpm AS base

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

FROM base AS build

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

FROM build AS dev

RUN /usr/local/bin/composer completion bash | tee /etc/bash_completion.d/composer

RUN echo '\nif [ -f /etc/bash_completion ] && ! shopt -oq posix; then\n    . /etc/bash_completion\nfi\n' >> ~/.bashrc
RUN echo 'eval "$(/var/www/website/bin/console completion bash )"\n' >> ~/.bashrc

RUN install-php-extensions xdebug

FROM base AS prod

COPY . /var/www/website

RUN chown -R www-data:www-data /var/www/website/var \
    && chmod -R 775 /var/www/website/var \
