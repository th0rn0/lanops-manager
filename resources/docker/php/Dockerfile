FROM php:8-fpm as php-base
LABEL maintainer="Thornton Phillis (dev@th0rn0.co.uk)"

RUN apt-get update && apt-get install -y \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
        libicu-dev \
        libzip-dev \
        libxml2-dev \
        libonig-dev \
    && docker-php-ext-install mysqli pdo_mysql gd bcmath intl zip xml mbstring exif

COPY src/ /var/www/html

RUN chown -R www-data:www-data /var/www

WORKDIR /var/www/html

COPY resources/docker/php/entrypoint.sh /custom-entrypoint.sh

COPY resources/docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

RUN chmod +x /custom-entrypoint.sh

ENTRYPOINT ["/custom-entrypoint.sh"]

FROM php-base as php-composer

RUN apt install -y git curl wget

RUN wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet

RUN mv composer.phar /usr/local/bin/composer
