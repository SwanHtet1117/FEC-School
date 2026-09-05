FROM php:8.4-apache

# Apache Rewrite Module ကို ဖွင့်ခြင်း (Laravel Routing အတွက်)
RUN a2enmod rewrite

# လိုအပ်သော PHP Extensions များသွင်းခြင်း
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql

# Composer ကို Docker ထဲသို့ ထည့်သွင်းခြင်း
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Project File များကို ကူးယူခြင်း
WORKDIR /var/www/html
COPY . .

# Laravel Vendor Folder (Dependencies) များကို သွင်းယူခြင်း
RUN composer install --no-dev --optimize-autoloader

# Apache Document Root ကို Laravel /public သို့ ပြောင်းခြင်း
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# Folder Permissions ပေးခြင်း
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80