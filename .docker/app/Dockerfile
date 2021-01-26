FROM php:7.4-fpm
MAINTAINER Mateusz Osetek "osetek.mateusz@gmail.com"

COPY . /app
WORKDIR /app

RUN apt-get update && apt-get install -y --no-install-recommends \
    acl \
    apt-transport-https \
    apt-utils \
    build-essential \
    curl \
    debconf \
    git \
    libcurl4-openssl-dev \
    mailutils \
    libssl-dev \
    pkg-config \
    wget \
    vim \
    unzip \
    zlib1g-dev

COPY .docker/app/php.ini /usr/local/etc/php/php.ini
COPY .docker/app/php-fpm-pool.conf 	/usr/local/etc/php/pool.d/www.conf

RUN pecl install mongodb
RUN echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/mongodb.ini

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer

RUN composer install --ignore-platform-reqs

RUN ln -snf /usr/share/zoneinfo/Europe/Lisbon /etc/localtime

RUN mkdir -p var && chown -R www-data:www-data var && chmod -R 777 var
RUN chmod -R 775 /var/www

EXPOSE 9000
CMD ["php-fpm"]
