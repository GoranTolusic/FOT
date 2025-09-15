FROM php:8.3-fpm

WORKDIR /var/www/html

# System dependencies and PHP extensions required for application
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    zlib1g-dev \
    libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql intl mbstring zip opcache curl \
    && apt-get clean

# Copy opcache .ini to enable caching for better performance
COPY php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Composer installation
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-scripts

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Set entrypoint
ENTRYPOINT ["/usr/local/bin/docker-entrypoint.sh"]

# PHP-FPM default port
EXPOSE 9000
