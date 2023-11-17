FROM php:8.2-fpm-alpine

# Copy composer binary from official image
COPY --from=composer/composer:latest-bin /composer /usr/bin/composer

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd

COPY --chown=1000:1000 . /app
COPY --chown=1000:1000 ./docker/php.ini /usr/local/etc/php/conf.d/php.ini
VOLUME app

WORKDIR /app
USER 1000:1000

RUN composer install