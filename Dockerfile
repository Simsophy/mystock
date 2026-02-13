############################
# Stage 1: Builder
############################
FROM php:8.2-fpm-alpine AS builder

# Install system dependencies
RUN apk add --no-cache \
    curl \
    git \
    zip \
    unzip \
    libzip-dev \
    oniguruma-dev \
    mysql-client

# Install required PHP extensions for Laravel
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Install PHP dependencies
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-interaction --optimize-autoloader

# Generate Laravel key (ignore if no .env yet)
RUN php artisan key:generate --force || true

# Create required directories
RUN mkdir -p storage/framework/{cache,sessions,views} storage/logs


############################
# Stage 2: Runtime
############################
FROM php:8.2-fpm-alpine

# Install runtime dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libzip-dev \
    oniguruma-dev \
    mysql-client

# Install PHP extensions again in runtime
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    bcmath

# Set working directory
WORKDIR /var/www/html

# Copy built app from builder
COPY --from=builder /var/www/html /var/www/html

# Copy config files (make sure these exist)
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set correct permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# Create supervisor log dir
RUN mkdir -p /var/log/supervisor

# Expose HTTP port
EXPOSE 80

# Start supervisor
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
