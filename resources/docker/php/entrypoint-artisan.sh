#!/usr/bin/env sh

php artisan optimize
php artisan config:cache

php artisan "$@"