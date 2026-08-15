FROM php:8.2-apache

# Install required system packages, image tools (ImageMagick, GraphicsMagick, NetPBM, Ghostscript), and development libraries
RUN apt-get update && apt-get install -y --no-install-recommends \
    imagemagick \
    graphicsmagick \
    netpbm \
    ghostscript \
    libmagickwand-dev \
    libpng-dev \
    libjpeg-dev \
    libwebp-dev \
    libfreetype6-dev \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    unzip \
    git \
    && rm -rf /var/lib/apt/lists/*

# Enable PDF, EPS, and PS processing in ImageMagick policy
RUN sed -i 's/rights="none" pattern="PDF"/rights="read|write" pattern="PDF"/' /etc/ImageMagick-*/policy.xml 2>/dev/null || true \
    && sed -i 's/rights="none" pattern="EPS"/rights="read|write" pattern="EPS"/' /etc/ImageMagick-*/policy.xml 2>/dev/null || true \
    && sed -i 's/rights="none" pattern="PS"/rights="read|write" pattern="PS"/' /etc/ImageMagick-*/policy.xml 2>/dev/null || true \
    && sed -i 's/rights="none" pattern="XPS"/rights="read|write" pattern="XPS"/' /etc/ImageMagick-*/policy.xml 2>/dev/null || true

# Configure and install PHP extensions and Imagick PECL module
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        gd \
        mysqli \
        pdo_mysql \
        intl \
        zip \
        mbstring \
        opcache \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Enable Apache modules required for phpwcms
RUN a2enmod rewrite headers

# Configure ServerName and AllowOverride for .htaccess support
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && { \
        echo '<Directory /var/www/html>'; \
        echo '    Options Indexes FollowSymLinks'; \
        echo '    AllowOverride All'; \
        echo '    Require all granted'; \
        echo '</Directory>'; \
    } > /etc/apache2/conf-available/phpwcms.conf \
    && a2enconf phpwcms

# Custom PHP settings for phpwcms development/testing
RUN { \
    echo 'memory_limit = 256M'; \
    echo 'upload_max_filesize = 64M'; \
    echo 'post_max_size = 64M'; \
    echo 'max_execution_time = 300'; \
    echo 'date.timezone = UTC'; \
    echo 'display_errors = On'; \
    echo 'display_startup_errors = On'; \
    echo 'error_reporting = E_ALL'; \
} > /usr/local/etc/php/conf.d/phpwcms.ini

WORKDIR /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
