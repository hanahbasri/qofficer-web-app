FROM php:8.2-cli

# Ekstensi & tools yang dibutuhkan Laravel + MySQL
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libzip-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip bcmath \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Salin kode & install dependency (vendor dibuat fresh, tidak dari repo)
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Izinkan built-in server PHP melayani banyak request sekaligus (bukan 1 per waktu)
ENV PHP_CLI_SERVER_WORKERS=8

# Railway menyuntik $PORT. Migrasi + seed lalu jalankan server.
# Pakai ';' bukan '&&' supaya serve tetap jalan walau seed sudah pernah sukses.
CMD php artisan migrate --force --seed --no-interaction; \
    php artisan serve --host 0.0.0.0 --port ${PORT:-8080}
