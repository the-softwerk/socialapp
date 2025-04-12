FROM php:8.3-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    mariadb-client \
    nodejs \
    npm \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd \
 && docker-php-ext-install pdo pdo_mysql zip intl \
 && curl -sS https://getcomposer.org/installer | php \
 && mv composer.phar /usr/local/bin/composer
