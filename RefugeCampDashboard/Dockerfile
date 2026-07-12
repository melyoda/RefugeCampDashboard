# Use an official lightweight PHP image with Apache web server
FROM php:8.2-apache

# Install required system dependencies and PHP extensions for MySQL and zip tools
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql mysqli zip \
    && a2enmod rewrite

# Install Composer into the container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set our working directory inside the container
WORKDIR /var/www/html

# Copy the application source code
COPY . .

# Run composer install to pull dependencies
RUN composer install --no-dev --optimize-autoloader

# Adjust Apache configuration to serve from the CodeIgniter /public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Give Apache permissions to the writeable directories
RUN chown -W -R www-data:www-data /var/www/html/writable

# Expose the default Apache port
EXPOSE 80