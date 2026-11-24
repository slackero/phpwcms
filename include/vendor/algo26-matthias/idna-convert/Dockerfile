FROM jitesoft/phpunit:8.5

ARG INFECTION_VERSION=0.34.2
ARG INFECTION_SHA256=ae83bc5151579817c3a6217eb3deceb86a169a399139264534218b723320da3d
ARG PCOV_VERSION=1.0.12

RUN apk add --no-cache icu-dev $PHPIZE_DEPS \
    && docker-php-ext-install intl \
    && pecl install "pcov-${PCOV_VERSION}" \
    && docker-php-ext-enable pcov \
    && apk del $PHPIZE_DEPS

RUN rm -f /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

COPY docker/pcov.ini /usr/local/etc/php/conf.d/pcov.ini

RUN curl --fail --location --silent --show-error \
        "https://github.com/infection/infection/releases/download/${INFECTION_VERSION}/infection.phar" \
        --output /usr/local/bin/infection \
    && echo "${INFECTION_SHA256}  /usr/local/bin/infection" | sha256sum -c \
    && chmod +x /usr/local/bin/infection
