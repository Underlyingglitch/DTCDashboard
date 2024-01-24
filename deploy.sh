#!/bin/bash
set -e

echo "Deployment started ..."

# Fetch the latest changes from the remote repository
git fetch

# Check if "force" is passed as an argument
if [[ "$1" != "force" ]]; then
    # Check if there are changes that have not been pulled yet
    if [ -z "$(git diff origin/main)" ]; then
        echo "No changes to pull from the remote repository."
        exit 0
    fi
fi

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down) || true

# Copy the current composer.json, package.json, and vite.config.js files
cp composer.json composer.json.bak
cp package.json package.json.bak
cp vite.config.js vite.config.js.bak
# Copy the current resources/js and resources/scss directories
cp -r resources/js resources/js.bak
cp -r resources/scss resources/scss.bak

# Reset the git repository
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

# Make sure the directories are writable for the webserver
# NOTE: This will make the entire /var/www directory writable for the webserver
#       which is not ideal. You should change the permissions to the specific
#       directories that need to be writable by the webserver.
chown -R www-data:www-data /var/www/

# Reload PHP to update opcache
# NOTE: This will only work if you have php-fpm installed
#       and if you change the line below to match your version
echo "" | sudo -S service php8.3-fpm reload

# Restart queue workers
php artisan queue:restart

# Exit maintenance mode
php artisan up

echo "Deployment finished!"

# Remove the backup files
rm composer.json.bak package.json.bak vite.config.js.bak
rm -r resources/js.bak resources/scss.bak
# If install.log exists, remove it
[ -f install.log ] && rm install.log
