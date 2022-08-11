FROM composer:2.3.10 AS builder

WORKDIR /app
COPY composer.json ./
COPY composer.lock ./
RUN composer install

FROM php:8.1.0-apache AS app

RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

WORKDIR /var/www/html/

COPY --from=builder /app/vendor ./vendor

COPY ./lang ./lang
COPY ./assets ./assets
COPY ./src ./

COPY .htaccess ./