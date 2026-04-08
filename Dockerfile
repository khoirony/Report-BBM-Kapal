FROM php:8.5-fpm

# Instal ekstensi sistem yang dibutuhkan oleh Laravel & Composer
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libonig-dev

# Instal ekstensi PHP untuk koneksi ke database MySQL
RUN docker-php-ext-install pdo_mysql mbstring

# Mengambil Composer dari image resminya
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set folder kerja default
WORKDIR /var/www/html