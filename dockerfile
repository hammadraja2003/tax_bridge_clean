# Use the official PHP 8.2 FPM Alpine image
FROM php:8.3-fpm-alpine
 
# Set working directory
WORKDIR /var/www
 
# Install system dependencies and Nginx
RUN apk update && apk add --no-cache \
    build-base \
    autoconf \
    g++ \
    make \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    oniguruma-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    unixodbc-dev \
    bash \
    krb5-libs \
    krb5-dev \
    openldap-dev \
    nginx \
    supervisor && \
    rm -rf /var/cache/apk/*
 
# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
        gd \
        mbstring \
        exif \
        pcntl \
        bcmath \
        zip \
        mysqli \
        pdo \
        pdo_mysql
 
# Install Composer
COPY --from=composer:2.6.5 /usr/bin/composer /usr/bin/composer
 
# Copy configuration files
COPY php.ini /usr/local/etc/php/conf.d/php.ini
COPY www.conf /usr/local/etc/php-fpm.d/www.conf
COPY default.conf /etc/nginx/conf.d/default.conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
 
# Copy application code
COPY . .
 
# Copy Laravel .env file
COPY .env /var/www/.env
 
# Install Laravel dependencies
RUN composer install --no-dev --optimize-autoloader
 
# Create necessary Laravel and runtime directories
RUN mkdir -p /var/run /var/log/nginx /var/log/php-fpm /tmp \
    /var/www/public/uploads/buyer_images \
    /var/www/storage/framework/cache \
    /var/www/storage/framework/sessions \
    /var/www/storage/framework/views \
    /var/www/bootstrap/cache && \
    chmod 1777 /tmp && \
    chmod -R 775 /var/www/storage /var/www/bootstrap/cache && \
    chown -R www-data:www-data /var/www /var/run /var/log /tmp
 
# Expose ports
EXPOSE 80 9000
 
# Start supervisord (will manage php-fpm + nginx)
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]