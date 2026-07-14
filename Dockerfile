# Use an official lightweight PHP image with Apache web server
FROM php:8.2-apache

# Install all system library configurations needed by CodeIgniter 4 + Shield
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    git \
    libicu-dev \
    libxml2-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install pdo_mysql mysqli zip intl xml \
    && a2enmod rewrite

# Install Composer cleanly into our environment
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory inside the container
WORKDIR /var/www/html

# Copy the flattened root project files directly into the container
COPY . .

# Run composer installation smoothly
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Adjust Apache configuration to serve directly from the CodeIgniter /public folder
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Give Apache full permissions to write to session, log, and cache folders
RUN chown -R www-data:www-data /var/www/html/writable

# Expose the default container web port
EXPOSE 80

# # RUNTIME FIX: When container boots, copy the cert to a place www-data can read, change ownership, and start Apache
# CMD ["sh", "-c", "mkdir -p /var/www/html/writable && cp /etc/secrets/ca.pem /var/www/html/writable/ca.pem && chown -R www-data:www-data /var/www/html/writable && apache2-foreground"]

# RUNTIME FIX: Dump system environment variables into Apache config pool, copy cert, and boot Apache
CMD ["sh", "-c", "mkdir -p /var/www/html/writable && cp /etc/secrets/ca.pem /var/www/html/writable/ca.pem && chown -R www-data:www-data /var/www/html/writable && env | grep -E '^(DATABASE_|CI_)' >> /etc/apache2/envvars && apache2-foreground"]
