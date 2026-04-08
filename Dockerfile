FROM php:8.2-fpm

# Instal dependensi sistem
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev

# Instal ekstensi PHP untuk MySQL
RUN docker-php-ext-install pdo_mysql mbstring

# Ambil Composer terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html