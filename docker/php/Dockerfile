# Use PHP 8.2 FPM as base
FROM php:8.2-fpm

WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd mbstring pdo pdo_mysql zip exif pcntl bcmath opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy composer files first for caching
COPY composer.json composer.lock ./

# Install dependencies WITHOUT running scripts
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts

# Copy rest of the application
COPY . .

# Ensure storage and cache directories exist and writable
RUN mkdir -p storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy custom PHP settings
COPY docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
