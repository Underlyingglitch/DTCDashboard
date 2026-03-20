# syntax=docker/dockerfile:1

ARG PHP_VERSION=8.5.3
ARG PHP_EXTS="pdo_mysql mbstring exif pcntl bcmath gd zip"
ARG PHP_PECL_EXTS="redis"

# 1) Build PHP extensions once (CLI base is fine)
FROM php:${PHP_VERSION}-alpine AS php_ext_builder
ARG PHP_EXTS
ARG PHP_PECL_EXTS

RUN set -eux; \
    apk add --no-cache --virtual .build-deps \
    $PHPIZE_DEPS \
    openssl ca-certificates \
    libxml2-dev oniguruma-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    libzip-dev \
    ; \
    # runtime libs required by built extensions
    apk add --no-cache \
    libpng libjpeg-turbo freetype \
    libzip \
    ; \
    docker-php-ext-configure gd --with-freetype --with-jpeg; \
    docker-php-ext-install -j"$(nproc)" ${PHP_EXTS}; \
    pecl install ${PHP_PECL_EXTS}; \
    docker-php-ext-enable ${PHP_PECL_EXTS}; \
    apk del .build-deps

# 2) Composer deps (must run with required PHP extensions available, e.g. gd)
FROM php_ext_builder AS composer_deps
WORKDIR /opt/apps/laravel

# install composer (alpine-friendly)
RUN set -eux; \
    apk add --no-cache curl; \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer; \
    apk del curl

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts

# 3) Frontend build
FROM node:20-alpine AS frontend
WORKDIR /opt/apps/laravel
# Copy only what the frontend build needs (adjust to your repo)
COPY package.json package-lock.json* pnpm-lock.yaml* yarn.lock* ./
RUN if [ -f package-lock.json ]; then npm ci; else npm install; fi
COPY . .
RUN npm run build

# 4) Common runtime base for PHP (CLI-like) with extensions copied in
FROM php:${PHP_VERSION}-alpine AS php_runtime
WORKDIR /opt/apps/laravel

# runtime libs required by your compiled extensions (gd/zip etc.)
RUN apk add --no-cache \
    ca-certificates openssl \
    libpng libjpeg-turbo freetype \
    libzip

# extensions + ini enables
COPY --from=php_ext_builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=php_ext_builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

# config/certs
COPY docker/php.ini /usr/local/etc/php/php.ini
COPY docker/cacert.pem /etc/ssl/certs/cacert.pem
RUN chown www-data:www-data /etc/ssl/certs/cacert.pem

# app code + vendor + built assets
COPY --from=composer_deps /opt/apps/laravel/vendor /opt/apps/laravel/vendor
COPY . /opt/apps/laravel
COPY --from=frontend /opt/apps/laravel/public /opt/apps/laravel/public

RUN set -eux; \
    mkdir -p bootstrap/cache storage/framework/{cache,sessions,views} storage/logs; \
    chown -R www-data:www-data bootstrap/cache storage; \
    chmod -R ug+rwX bootstrap/cache storage

# default user for runtime containers
USER www-data

# 5) CLI target
FROM php_runtime AS cli
USER root
RUN set -eux; \
    echo '* * * * * cd /opt/apps/laravel && php artisan schedule:run >> /proc/1/fd/1 2>> /proc/1/fd/2' > /etc/crontabs/www-data
USER www-data
CMD ["php", "artisan", "help"]

# # 6) Queue worker target
# FROM php_runtime AS queue_worker
# CMD ["php", "artisan", "queue:work", "--verbose", "--tries=3", "--timeout=90"]

# # 7) Socket/Reverb target
# FROM php_runtime AS socket_server
# CMD ["php", "artisan", "reverb:start"]

# # 8) Cron target
# FROM php_runtime AS cron
# USER root
# RUN set -eux; \
#     echo '* * * * * cd /opt/apps/laravel && php artisan schedule:run >> /proc/1/fd/1 2>> /proc/1/fd/2' > /etc/crontabs/www-data
# USER www-data
# CMD ["crond", "-l", "2", "-f"]

# 9) FPM target (don’t rebuild extensions; just start from fpm and copy artifacts)
FROM php:${PHP_VERSION}-fpm-alpine AS fpm_server
WORKDIR /opt/apps/laravel

RUN apk add --no-cache \
    ca-certificates openssl \
    libpng libjpeg-turbo freetype \
    libzip

COPY --from=php_ext_builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=php_ext_builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

COPY docker/php.ini /usr/local/etc/php/php.ini
COPY docker/cacert.pem /etc/ssl/certs/cacert.pem
RUN chown www-data:www-data /etc/ssl/certs/cacert.pem

COPY docker/fpm-entrypoint.sh /opt/apps/laravel/entrypoint.sh
RUN chmod +x /opt/apps/laravel/entrypoint.sh

COPY --from=composer_deps /opt/apps/laravel/vendor /opt/apps/laravel/vendor
COPY . /opt/apps/laravel
COPY --from=frontend /opt/apps/laravel/public /opt/apps/laravel/public

RUN set -eux; \
    mkdir -p bootstrap/cache storage/framework/{cache,sessions,views} storage/logs; \
    chown -R www-data:www-data bootstrap/cache storage; \
    chmod -R ug+rwX bootstrap/cache storage

USER www-data
ENTRYPOINT ["./entrypoint.sh"]

# 10) Nginx target
FROM nginx:stable-alpine AS web_server
WORKDIR /opt/apps/laravel
COPY docker/nginx.conf /etc/nginx/templates/default.conf.template
COPY --from=frontend /opt/apps/laravel/public /opt/apps/laravel/public

# Default stage (your choice)
FROM cli