FROM php:8.5-fpm-alpine

RUN apk add --no-cache git unzip icu-libs libzip \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS icu-dev libzip-dev linux-headers \
    && docker-php-ext-install intl pdo_mysql zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY php.ini /usr/local/etc/php/conf.d/zz-app.ini

# Run as the host user so files created in the container (composer, make:*)
# stay editable on the host.
ARG UID=1000
ARG GID=1000
RUN addgroup -g ${GID} app 2>/dev/null || true \
    && adduser -D -u ${UID} -G $(getent group ${GID} | cut -d: -f1) app 2>/dev/null || true

WORKDIR /app
USER ${UID}:${GID}
