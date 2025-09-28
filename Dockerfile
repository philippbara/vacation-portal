FROM php:8.3-cli

# Install extensions
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Set working directory
WORKDIR /var/www/html

# Copy source
COPY . .

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install

# Expose port
EXPOSE 8000

# Run PHP built-in server
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public", "public/index.php"]
