# Dockerfile (simpan di root proyek Laravel Anda, misal: /admin-app/Dockerfile dan /trainer-app/Dockerfile)

# --- Stage 1: Build PHP dependencies ---
FROM composer:2 as vendor

WORKDIR /app
COPY database/ database/
COPY composer.json composer.json
COPY composer.lock composer.lock
RUN composer install --ignore-platform-reqs --no-interaction --no-plugins --no-scripts --prefer-dist

# --- Stage 2: Build frontend assets (jika ada) ---
# Jika Anda menggunakan NPM/Yarn untuk build assets, tambahkan stage ini
# FROM node:18 as frontend
# WORKDIR /app
# COPY package.json package.json
# COPY yarn.lock yarn.lock # atau package-lock.json
# COPY webpack.mix.js webpack.mix.js
# COPY resources/ resources/
# RUN yarn install # atau npm install
# RUN yarn prod # atau npm run prod

# --- Stage 3: Setup PHP-FPM Application ---
FROM php:8.4-fpm-alpine as app 

WORKDIR /var/www/html

# Instal ekstensi PHP yang umum dibutuhkan Laravel
RUN apk add --no-cache \
    curl \
    libpng-dev \
    libzip-dev \
    jpeg-dev \
    freetype-dev \
    nginx \
    supervisor \
    # Tambahkan ekstensi lain jika perlu (misal: pdo_mysql, bcmath, gd, zip)
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install pdo pdo_mysql bcmath zip exif pcntl

# Kopi file aplikasi dari direktori saat ini ke dalam image
COPY . /var/www/html

# Kopi vendor dari stage 'vendor'
COPY --from=vendor /app/vendor/ /var/www/html/vendor/

# Kopi assets dari stage 'frontend' (jika ada)
# COPY --from=frontend /app/public/build /var/www/html/public/build

# Set permission yang benar
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Hapus cache konfigurasi jika ada (Cloud Run akan inject environment variables)
RUN php artisan config:clear && php artisan route:clear && php artisan view:clear

# --- Stage 4: Setup Nginx & Supervisor ---
# Kopi konfigurasi Nginx
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
# Kopi konfigurasi Supervisor
COPY docker/supervisor.conf /etc/supervisor/conf.d/app.conf

# Expose port 8080 (Cloud Run mengharapkan container listen di port yang diset oleh env $PORT, default 8080)
EXPOSE 8080

# Jalankan Supervisor untuk mengelola Nginx dan PHP-FPM
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/app.conf"]