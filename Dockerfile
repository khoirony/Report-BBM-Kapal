FROM php:8.4-fpm

# 1. Instal dependensi sistem (Termasuk Library untuk GD)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev

# 2. Konfigurasi dan Instal semua ekstensi PHP sekaligus
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo_mysql \
    mbstring \
    bcmath \
    xml \
    zip \
    intl \
    gd

# 3. Ambil Composer terbaru dari image resmi
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Set folder kerja
WORKDIR /var/www/html