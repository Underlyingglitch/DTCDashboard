#!/bin/bash
set -e

echo "Deployment started ..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down) || true

# Copy the current composer.json, package.json, and vite.config.js files
cp composer.json composer.json.bak
cp package.json package.json.bak
cp vite.config.js vite.config.js.bak
cp -r resources/js resources/js.bak
cp -r resources/scss resources/scss.bak

# Pull the latest version of the app
git fetch --all
git reset --hard origin/main

# Check if composer.json has changed
if ! cmp -s composer.json composer.json.bak; then
    # Install composer dependencies
    echo "Installing composer dependencies ..."
    composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader > install.log 2>&1
else
    echo "No changes in composer.json. Skipping installation of composer dependencies."
fi

# Clear the old cache
php artisan clear-compiled

# Recreate cache
php artisan optimize

if ! cmp -s package.json package.json.bak; then
    # Compile npm assets
    echo "Installing npm packages ..."
    npm install >> install.log 2>&1
else
    echo "No changes in package.json. Skipping installation of npm packages."
fi

if ! cmp -s vite.config.js vite.config.js.bak || ! diff -qr resources/js resources/js.bak || ! diff -qr resources/scss resources/scss.bak; then
    # Compile npm assets
    echo "Compiling npm assets ..."
    npm run build >> install.log 2>&1
else
    echo "No changes in vite.config.js, resources/js or resources/scss. Skipping compilation of npm assets."
fi

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

rm composer.json.bak package.json.bak vite.config.js.bak
rm -r resources/js.bak resources/scss.bak