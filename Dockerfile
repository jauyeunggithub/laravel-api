# Use the official PHP image with Apache server (PHP 8.2)
FROM php:8.2-apache

# Install dependencies for GD, MySQL, and other required libraries
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    libicu-dev \
    libxml2-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions: GD, PDO, and MySQL
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd pdo pdo_mysql intl

# Enable Apache mod_rewrite and other necessary Apache modules
RUN a2enmod rewrite

# Configure Apache to serve Laravel's public folder
RUN echo 'DocumentRoot /var/www/html/public' > /etc/apache2/sites-available/000-default.conf

# Copy the Laravel files into the container
COPY . /var/www/html

# Set working directory to /var/www/html
WORKDIR /var/www/html

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Expose Apache port
EXPOSE 80

# Run Apache in the foreground
CMD ["apache2-foreground"]
