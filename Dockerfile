FROM php:7.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

#Copying Host Directory
#COPY . .

# Copying Browscap Dependencies
COPY browscap.ini  /usr/local/etc/php/conf.d/

#Reconifugring php.ini to suppoer browscap
RUN sed -i 's#;browscap = extra/browscap.ini#browscap = /usr/local/etc/php/conf.d/browscap.ini#g' /usr/local/etc/php/php.ini-production

#Moving Production to php.ini
RUN mv /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini
#Installing Dependencies
#RUN composer install

#Changing owner to www
RUN chown -R www-data:www-data /var/www

