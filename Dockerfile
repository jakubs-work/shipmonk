FROM php:8.4-fpm-alpine

# 1. Install system dependencies
RUN apk add --no-cache \
    bash \
    git \
    icu-dev \
    libpq-dev \
    libzip-dev \
    zip

# 2. Download the helper script for easy extension installation
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# 3. Install PHP extensions + Xdebug using the helper
RUN install-php-extensions \
    intl \
    pdo \
    pdo_mysql \
    zip \
    xdebug

# 4. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
