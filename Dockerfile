FROM php:8.0-apache

COPY . /var/www/html
RUN apt-get update && \
    apt-get install -y libxml2-dev
RUN docker-php-ext-install soap
RUN docker-php-ext-install mysqli pdo pdo_mysql
