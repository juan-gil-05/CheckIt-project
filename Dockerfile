# To get php version 8.1 with an apache server
FROM php:8.1-apache

# To install the extentions nedded to use mysql, and mongodb
RUN apt-get update && apt-get install -y \
    libssl-dev pkg-config git unzip zip \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb 

# To activate the rewrite on apache server
RUN a2enmod rewrite

# To install composer into the docker container
COPY --from=composer:2.8.9 /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/

# To set the server apache config
COPY apache-site.conf /etc/apache2/sites-available/000-default.conf

COPY Public/.htaccess /var/www/html/Public