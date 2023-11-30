ARG PHP_VERSION=8.2
FROM --platform=linux/amd64 serversideup/php:${PHP_VERSION}-fpm-apache-v2.2.0

# Install missing software
ARG NODE_MAJOR=20
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
      build-essential git vim ca-certificates curl gnupg \
      php8.2-gd php8.2-imagick \
      php8.2-xml php8.2-dev php8.2-mysql \
      php8.2-curl php8.2  php8.2-common \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Set PHP_INI_DIR for docker-php-ext-enable to use
ARG PHP_VERSION
ENV PHP_INI_DIR=/etc/php/${PHP_VERSION}/fpm
