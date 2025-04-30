FROM php:8.2-apache

# Install required PHP extensions - used for MySQLi calls
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Enable mod_rewrite
RUN a2enmod rewrite

# Install required PHP extensions and Composer
RUN apt-get update && apt-get install -y \
    libonig-dev libzip-dev unzip curl \
    && docker-php-ext-install pdo pdo_mysql mbstring zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project files
COPY src/ /var/www/html/
COPY composer.json /var/www/html/
WORKDIR /var/www/html/

# Install PHP dependencies
RUN composer install

# Set permissions
RUN chown -R www-data:www-data /var/www/html
