FROM php:8.4-fpm AS base

ARG USER_ID=33
ARG GROUP_ID=33
ARG APP_ENV=prod

ENV APP_ENV=${APP_ENV}

# Utilisation de la version officielle de l'extension installer
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync

# Installation des dépendances système et extensions PHP
RUN apt-get update -qq && \
    apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libxml2-dev \
    libxslt-dev && \
    rm -rf /var/lib/apt/lists/*

# Extensions PHP nécessaires pour Symfony et PostgreSQL
RUN install-php-extensions \
    intl \
    opcache \
    pdo_pgsql \
    pgsql \
    zip \
    apcu \
    gd \
    dom \
    xsl \
    sysvsem

COPY ./docker/php/common-php.ini /usr/local/etc/php/conf.d/common-php.ini

RUN if [ ${USER_ID} -ne 33 ] || [ ${GROUP_ID} -ne 33 ]; then \
    usermod -u ${USER_ID} www-data && \
    groupmod -g ${GROUP_ID} www-data \
; fi

WORKDIR /var/www/kiora-crm

FROM registry.kiora.tech/kiora/kiora-crm_php_base:0.0.2 AS build

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer \
    --2

# Étape de développement
FROM build AS dev

# Installation des outils de développement
RUN install-php-extensions xdebug pcov

COPY ./docker/php/dev-php.ini /usr/local/etc/php/conf.d/dev-php.ini

USER www-data

# Étape de production
FROM registry.kiora.tech/kiora/kiora-crm_php_base:0.0.2 AS prod

USER root

COPY --chown=www-data:www-data . .

# Configuration PHP spécifique production
COPY ./docker/php/prod-php.ini /usr/local/etc/php/conf.d/prod-php.ini

USER www-data

# Étape optionnelle pour Supervisor
FROM prod AS supervisor

USER root

RUN apt-get update -qq && \
    apt-get install -y supervisor && \
    rm -rf /var/lib/apt/lists/*

COPY ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]