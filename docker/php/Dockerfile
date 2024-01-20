FROM php:7-fpm
LABEL maintainer="Thornton Phillis (dev@th0rn0.co.uk)"

RUN apt-get update && apt-get install -y \
		libfreetype-dev \
		libjpeg62-turbo-dev \
		libpng-dev \
        libicu-dev \
    && docker-php-ext-install mysqli pdo_mysql gd bcmath intl