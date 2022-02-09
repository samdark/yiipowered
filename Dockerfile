FROM php:7.4.9-fpm-alpine

RUN apk update --no-cache \
    && apk add --no-cache \
        autoconf \
        bash \
        dcron \
        icu-dev \
        libzip-dev \
        zip \
        libxml2-dev \
        nano \
        libpng libpng-dev libjpeg-turbo-dev libwebp-dev zlib-dev libxpm-dev \
        npm \
    && docker-php-ext-install -j$(nproc) \
        zip \
        intl \
        opcache \
        xml \
        pdo \
        pdo_mysql \
        gd \
    && apk del libpng-dev libjpeg-turbo-dev libwebp-dev zlib-dev libxpm-dev \
    && mv $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini \
    && sed -i "s/expose_php = On/expose_php = Off/g" $PHP_INI_DIR/php.ini \
    && rm /usr/local/etc/php-fpm.d/zz-docker.conf \
    && npm -g install less

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

ENV COMPOSER_MEMORY_LIMIT -1
RUN composer install

COPY ./docker/php-fpm-www-pool.conf /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/docker-cron-entrypoint /usr/local/bin/docker-cron-entrypoint

RUN chmod 777 /var/www/docker/crontab \
    && crontab /var/www/docker/crontab \
    && chown -R www-data:www-data /var/www/runtime \
    && chmod -R 777 /usr/local/bin/docker-cron-entrypoint

# Make binaries from composer packages
# available without path prefixes
ENV PATH ./vendor/bin:$PATH
