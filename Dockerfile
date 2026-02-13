# Stage 1: Builder
FROM php:8.2-fpm-alpine AS builder

# Install dependencies (same as before)
RUN apk add --no-cache curl git zip unzip libzip-dev sqlite-dev postgresql-dev mysql-client \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 🔹 Change WORKDIR
WORKDIR /var/www/html

# Copy app files
COPY . .

# Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader
RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-interaction --optimize-autoloader

# Generate APP_KEY
RUN php artisan key:generate --force || true

# Create necessary directories
RUN mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views

# Stage 2: Runtime
FROM php:8.2-fpm-alpine

# Install runtime dependencies
RUN apk add --no-cache nginx supervisor curl mysql-client libzip-dev sqlite-dev postgresql-dev \
    && docker-php-ext-install pdo pdo_mysql pdo_sqlite zip bcmath

# 🔹 Change WORKDIR
WORKDIR /var/www/html

# Copy from builder
COPY --from=builder /var/www/html /var/www/html

# Copy nginx & supervisor configs
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/conf.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Create supervisor log dir
RUN mkdir -p /var/log/supervisor && touch /var/log/supervisor/supervisord.log

# Expose ports
EXPOSE 80 9000

# Health check
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Start supervisor
CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
