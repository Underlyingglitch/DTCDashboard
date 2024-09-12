#!/bin/sh

php artisan event:cache
php artisan view:cache
php artisan route:cache

php-fpm