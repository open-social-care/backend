# Build stage
FROM php:8.2-cli AS builder

# Set working directory
WORKDIR /app

# Install dependencies for building
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set memory limit for Composer
RUN echo "memory_limit=-1" > /usr/local/etc/php/conf.d/99-composer.ini

# Configure git for safe directory
RUN git config --global --add safe.directory /app

# Copy composer files first
COPY composer.json composer.lock ./

# Install dependencies (including dev dependencies for Breeze)
RUN composer install --no-scripts --no-autoloader

# Copy only necessary application files
COPY app app
COPY bootstrap bootstrap
COPY config config
COPY database database
COPY public public
COPY resources resources
COPY routes routes
COPY storage storage
COPY artisan .

# Generate optimized autoload files
RUN composer dump-autoload --optimize

# Production stage
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Install only production dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Configure PHP for production
RUN echo 'opcache.memory_consumption=128' >> /usr/local/etc/php/conf.d/opcache-recommended.ini \
    && echo 'opcache.interned_strings_buffer=8' >> /usr/local/etc/php/conf.d/opcache-recommended.ini \
    && echo 'opcache.max_accelerated_files=4000' >> /usr/local/etc/php/conf.d/opcache-recommended.ini \
    && echo 'opcache.revalidate_freq=2' >> /usr/local/etc/php/conf.d/opcache-recommended.ini \
    && echo 'opcache.fast_shutdown=1' >> /usr/local/etc/php/conf.d/opcache-recommended.ini \
    && echo 'opcache.enable_cli=1' >> /usr/local/etc/php/conf.d/opcache-recommended.ini

# Configure Apache
RUN echo '<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog ${APACHE_LOG_DIR}/error.log\n\
    CustomLog ${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Copy application from builder stage
COPY --from=builder /app /var/www/html

# Create Laravel storage directories
RUN mkdir -p /var/www/html/storage/app/public \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/framework/testing \
    && mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/logs \
    && mkdir -p /var/www/html/bootstrap/cache

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Generate Laravel cache files
RUN php artisan key:generate --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# Remove unnecessary files
RUN rm -rf /var/www/html/node_modules \
    && rm -rf /var/www/html/tests \
    && find /var/www/html -name '.git*' -exec rm -rf {} +

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
