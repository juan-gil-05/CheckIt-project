FROM php:8.1-apache

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:2.8.9 /usr/bin/composer /usr/bin/composer

COPY apache-site.conf /etc/apache2/sites-available/000-default.conf