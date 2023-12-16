#!/bin/bash
set -e

echo "Deployment started ..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down) || true

# Pull the latest version of the app
git fetch --all
git reset --hard origin/main

# Install composer dependencies
echo "Installing composer dependencies ..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader > /dev/null 2>&1

# Clear the old cache
php artisan clear-compiled

# Recreate cache
php artisan optimize

# Compile npm assets
echo "Installing npm packages ..."
npm install > /dev/null 2>&1

# Compile npm assets
echo "Compiling npm assets ..."
npm run build > /dev/null 2>&1

# Run database migrations
php artisan migrate --force

chown -R www-data:www-data /var/www/

# Reload PHP to update opcache
echo "" | sudo -S service php8.2-fpm reload

# Restart queue workers
php artisan queue:restart

# Exit maintenance mode
php artisan up

echo "Deployment finished!"