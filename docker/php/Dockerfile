FROM php:8.3.32-fpm

COPY php.ini /usr/local/etc/php/php.ini


RUN apt-get update \
    && apt-get install -y \
    default-mysql-client \
    git \
    zip \
    unzip \
    zlib1g-dev \
    libzip-dev \
    libonig-dev \
    libicu-dev \
    curl \
    && docker-php-ext-install \
    pdo_mysql \
    zip \
    mbstring \
    opcache \
    bcmath \
    pcntl \
    intl \
    && pecl install redis \
    && docker-php-ext-enable redis

    # Node.js 20（Inertia.js / Vite ビルド用）
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN mkdir -p /var/www/.composer && chown -R 1000:1000 /var/www/.composer

WORKDIR /var/www/laravel

ENV COMPOSER_HOME=/var/www/.composer
ENV XDG_CONFIG_HOME=/var/www/.config

RUN mkdir -p /var/www/.composer /var/www/.config && chown -R 1000:1000 /var/www/.composer /var/www/.config