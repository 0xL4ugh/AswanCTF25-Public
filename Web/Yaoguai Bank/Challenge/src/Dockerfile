# Use an official PHP image with Apache
FROM php:7.4-apache

# Set environment variable (flag stored in ENV — consider securing it in production)
ENV FLAG=YAO{b4nk_s3cur1ty_writt3n_by_1nt3rn}

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install required PHP extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy application files to the web root
COPY . /var/www/html/

RUN mv /var/www/html/front/index.html /var/www/html/index.html

# Set correct permissions (optional but recommended)
RUN chown -R www-data:www-data /var/www/html

# Expose Apache port
EXPOSE 80
