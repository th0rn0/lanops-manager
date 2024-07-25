#!/usr/bin/env sh

php artisan config:cache

php artisan "$@"