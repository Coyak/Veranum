FROM php:8.1-apache

# Instalar la extensión pdo_mysql
RUN docker-php-ext-install pdo pdo_mysql 