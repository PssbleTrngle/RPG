FROM composer AS builder

WORKDIR /app
COPY composer.json ./
RUN composer update

FROM php:8.0.0-apache AS app

WORKDIR /var/www/html/

#RUN a2enmod rewrite

COPY --from=builder /app/vendor ./vendor

COPY ./lang ./lang
COPY ./assets ./assets
COPY ./src ./src

COPY .htaccess ./