ARG PHP_VERSION=8.2
FROM php:${PHP_VERSION}-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    unzip \
    msmtp \
    msmtp-mta \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j"$(nproc)" \
        gd \
        mysqli \
        pdo_mysql \
        intl \
        zip \
        mbstring \
        bcmath \
    && a2enmod rewrite headers

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && { \
        echo '<Directory /var/www/html>'; \
        echo '    Options Indexes FollowSymLinks'; \
        echo '    AllowOverride All'; \
        echo '    Require all granted'; \
        echo '</Directory>'; \
    } > /etc/apache2/conf-available/phpwcms.conf \
    && a2enconf phpwcms

RUN { \
        echo 'memory_limit = 256M'; \
        echo 'upload_max_filesize = 64M'; \
        echo 'post_max_size = 64M'; \
        echo 'max_execution_time = 300'; \
        echo 'date.timezone = UTC'; \
        echo 'display_errors = On'; \
        echo 'display_startup_errors = On'; \
        echo 'error_reporting = E_ALL'; \
        echo 'sendmail_path = "/usr/bin/msmtp -t --host=mailpit --port=1025"'; \
    } > /usr/local/etc/php/conf.d/phpwcms.ini

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
