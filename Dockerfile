# Use the official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies and PHP extensions
RUN apt-get update \
    && apt-get install -y libzip-dev unzip git \
    && docker-php-ext-install zip

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy project files to the Apache document root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Set permissions (optional, for uploads)
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80

# Start Apache in the foreground
# Render sets a dynamic $PORT; update Apache to listen on it at runtime
CMD ["/bin/sh", "-c", "set -e; : ${PORT:=80}; sed -i \"s/^Listen 80$/Listen ${PORT}/\" /etc/apache2/ports.conf; sed -i \"s/:80>/:${PORT}>/\" /etc/apache2/sites-available/000-default.conf || true; exec apache2-foreground"]
