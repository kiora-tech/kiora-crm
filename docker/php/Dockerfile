FROM php:8.4-fpm AS base

ARG USER_ID=33
ARG GROUP_ID=33

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync

RUN apt update \
    && apt install -y zlib1g-dev g++ git libicu-dev zip libzip-dev zip

RUN install-php-extensions intl opcache pdo_mysql zip apcu gd xml dom sysvsem xsl

COPY ./docker/php/common-php.ini /usr/local/etc/php/conf.d/common-php.ini

RUN if [ ${USER_ID} -ne 33 ] || [ ${GROUP_ID} -ne 33 ]; then \
    usermod -u ${USER_ID} www-data && \
    groupmod -g ${GROUP_ID} www-data \
; fi

WORKDIR /var/www/website

FROM base AS build

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

FROM build AS dev

COPY ./docker/php/dev-php.ini /usr/local/etc/php/conf.d/dev-php.ini

RUN /usr/local/bin/composer completion bash | tee /etc/bash_completion.d/composer

RUN echo '\nif [ -f /etc/bash_completion ] && ! shopt -oq posix; then\n    . /etc/bash_completion\nfi\n' >> ~/.bashrc
RUN echo 'eval "$(/var/www/website/bin/console completion bash )"\n' >> ~/.bashrc

RUN install-php-extensions xdebug

FROM base AS prod

COPY . /var/www/website

COPY ./docker/php/prod-php.ini /usr/local/etc/php/conf.d/prod-php.ini

#supervisor version
FROM prod AS supervisor

RUN apt-get update && apt-get install -y supervisor

COPY ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]