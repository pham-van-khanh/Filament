FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    intl \
    zip \
    exif \
    pcntl \
    bcmath \
    gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Install Node.js & npm (for Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && node -v \
    && npm -v

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

WORKDIR /var/www

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

# Fix Windows CRLF -> Linux LF
RUN sed -i 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["php-fpm"]
