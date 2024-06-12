   FROM php:8.3-fpm

   # Install system dependencies
   RUN apt-get update && apt-get install -y \
       build-essential \
       libpng-dev \
       libjpeg62-turbo-dev \
       libfreetype6-dev \
       libzip-dev \
       zip \
       unzip \
       git \
       curl

   RUN docker-php-ext-install pdo pdo_mysql gd zip

   # Set working directory
   WORKDIR /var/www

   COPY . /var/www

   RUN rm .env.example

   COPY .env.template .env

   # Install composer
   COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

   RUN composer install

   CMD ["php-fpm"]
   
