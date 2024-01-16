FROM php:8-fpm

# Install dependencies and extensions
RUN apt-get update && apt-get install -y \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        zip \
        unzip \
        git \
    && docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo pdo_mysql

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/client

# Expose port 9001 and start php-fpm server
EXPOSE 9001
CMD ["php-fpm"]
