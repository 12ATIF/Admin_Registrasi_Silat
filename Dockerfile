# Stage 1: Build dependencies
FROM composer:2.7 AS build
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-interaction --no-scripts || true

# Stage 2: Production
FROM php:8.3-fpm-alpine

# Install ekstensi and packages
RUN apk add --no-cache \
    bash \
    nginx \
    supervisor \
    postgresql-dev \
    libpng-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    xml \
    gd \
    zip \
    bcmath \
    intl \
    opcache

WORKDIR /var/www/app

# Copy applications
COPY --from=build /app /var/www/app

# Copy settings
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/supervisord.conf /etc/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh

# Fix PHP upload limits
RUN echo "upload_max_filesize=10M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=15M" >> /usr/local/etc/php/conf.d/uploads.ini

# Fix nginx tmp permissions and set permissions
RUN chmod +x /entrypoint.sh \
    && chown -R www-data:www-data /var/www/app \
    && chmod -R 775 /var/www/app/storage /var/www/app/bootstrap/cache || true \
    && chown -R www-data:www-data /var/lib/nginx \
    && chmod -R 755 /var/lib/nginx

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisord.conf"]
