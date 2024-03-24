#!/usr/bin/env sh
...
php artisan optimize
php artisan config:cache
php artisan view:cache
php artisan view:clear

docker-php-entrypoint

php-fpm