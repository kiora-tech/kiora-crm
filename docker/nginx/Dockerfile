FROM nginx:stable-alpine  AS base

COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

FROM base AS prod

COPY . /var/www/website

FROM base AS build

FROM build AS dev

VOLUME /var/www/website