# =====================================================================
# Stage 1: Compile Frontend Assets (Vite, Tailwind CSS, JS)
# =====================================================================
FROM node:20-alpine AS frontend
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY vite.config.js ./
COPY resources/ resources/
COPY public/ public/

RUN npm run build

# =====================================================================
# Stage 2: Install Composer Dependencies
# =====================================================================
FROM composer:2 AS backend
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# =====================================================================
# Stage 3: Production Runtime (PHP 8.2-FPM Alpine)
# =====================================================================
FROM php:8.2-fpm-alpine AS runtime

# System runtime dependencies & PHP build libraries
RUN apk add --no-cache \
    bash \
    curl \
    libpng \
    libpng-dev \
    libjpeg-turbo \
    libjpeg-turbo-dev \
    libwebp \
    libwebp-dev \
    freetype \
    freetype-dev \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    shadow \
    mysql-client

# Configure & Install PHP extensions required by Laravel
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        bcmath \
        opcache \
        pcntl \
        zip \
        exif \
        gd \
        intl

# Set working directory
WORKDIR /var/www/html

# Copy application code
COPY . .

# Copy production vendor from backend stage
COPY --from=backend /app/vendor ./vendor

# Copy compiled frontend assets from frontend stage
COPY --from=frontend /app/public/build ./public/build

# Setup entrypoint script
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm"]
