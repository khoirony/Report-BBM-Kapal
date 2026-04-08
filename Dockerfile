FROM php:8.4-fpm

# 1. Instal dependensi sistem (Library yang dibutuhkan oleh ekstensi PHP)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev

# 2. Instal semua ekstensi PHP sekaligus dalam satu layer
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    bcmath \
    xml \
    zip \
    intl

# 3. Ambil Composer terbaru dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set folder kerja
WORKDIR /var/www/html