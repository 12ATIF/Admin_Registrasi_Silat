FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev unzip curl libpng-dev libonig-dev libxml2-dev \
    zip git npm && \
    docker-php-ext-install pdo_mysql zip exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage

# Expose Cloud Run's required port
ENV PORT 8000

# Laravel serves app via php -S, so we run it:
CMD php -S 0.0.0.0:8000 -t public
