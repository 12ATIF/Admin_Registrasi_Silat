# Tahap 1: Build dependensi PHP dengan Composer
FROM composer:2.5 as vendor
WORKDIR /app
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Tahap 2: Build aset frontend dengan Node.js dan Vite
FROM node:18-alpine as assets
WORKDIR /app
COPY package.json package-lock.json ./
COPY resources/ resources/
COPY vite.config.js .
# Jika Anda memiliki tailwind.config.js dan postcss.config.js, aktifkan baris di bawah
# COPY tailwind.config.js .
# COPY postcss.config.js .
RUN npm install
RUN npm run build

# Tahap 3: Image aplikasi final
FROM php:8.2-fpm-alpine  # Menggunakan PHP 8.2
WORKDIR /var/www/html

# Instal ekstensi PHP umum dan Nginx + Supervisor
RUN apk add --no-cache \
    nginx supervisor \
    libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev icu-dev \
    # Tambahkan ekstensi lain yang mungkin dibutuhkan Laravel Anda
    # seperti: pdo_pgsql (jika pakai PostgreSQL), sockets, pcntl, exif, bcmath
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo pdo_mysql zip intl gd exif bcmath sockets pcntl

# Salin kode aplikasi
COPY . .
# Salin vendor dari tahap 'vendor'
COPY --from=vendor /app/vendor/ ./vendor/
# Salin aset yang sudah di-build dari tahap 'assets'
COPY --from=assets /app/public/build ./public/build

# Atur izin dengan benar
# Pastikan direktori ini ada sebelum chown jika tidak dibuat oleh COPY . .
RUN mkdir -p /var/www/html/storage/framework/sessions \
             /var/www/html/storage/framework/views \
             /var/www/html/storage/framework/cache/data \
             /var/www/html/storage/logs \
    && chown -R www-data:www-data \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
    && chmod -R 775 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache

# Konfigurasi Nginx
# PENTING: Sesuaikan path tujuan jika /etc/nginx/conf.d/ tidak ada di base image Alpine Nginx
# Biasanya /etc/nginx/http.d/ atau /etc/nginx/conf.d/
COPY .docker/nginx.conf /etc/nginx/nginx.conf
COPY .docker/site.conf /etc/nginx/conf.d/default.conf # Jika nginx.conf Anda meng-include dari conf.d

# Konfigurasi Supervisor
COPY .docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf 
# Atau jika supervisor Anda mencari di /etc/supervisord.conf langsung:
# COPY .docker/supervisord.conf /etc/supervisord.conf

# Pastikan direktori log untuk Supervisor ada dan dapat ditulis
RUN mkdir -p /var/log/supervisor && chown -R www-data:www-data /var/log/supervisor

# Ekspos port yang akan digunakan oleh Nginx di dalam kontainer
EXPOSE 8080

# Jalankan Supervisor
# CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
# Atau jika supervisor.conf disalin ke /etc/supervisor/conf.d/supervisord.conf
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
