ARG PHP_VERSION=8.2
FROM serversideup/php:${PHP_VERSION}-fpm-apache

# Install missing software
ARG NODE_MAJOR=20
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
      build-essential git vim ca-certificates curl gnupg \
      php-gd php-imagick php-pear php-dev \
    && mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list \
    && apt update \
    && apt install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* \
    && pecl install xdebug

# Set PHP_INI_DIR for docker-php-ext-enable to use
ARG PHP_VERSION
ENV PHP_INI_DIR=/etc/php/${PHP_VERSION}/fpm

# Setup Docker extensions (Xdebug)
ADD https://raw.githubusercontent.com/docker-library/php/master/docker-php-ext-enable /usr/bin/docker-php-ext-enable
RUN chmod u+x /usr/bin/docker-php-ext-enable \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /etc/php/${PHP_VERSION}/fpm/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=debug" >> /etc/php/${PHP_VERSION}/cli/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /etc/php/${PHP_VERSION}/fpm/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /etc/php/${PHP_VERSION}/cli/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /etc/php/${PHP_VERSION}/fpm/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_port=9003" >> /etc/php/${PHP_VERSION}/cli/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /etc/php/${PHP_VERSION}/fpm/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /etc/php/${PHP_VERSION}/cli/conf.d/docker-php-ext-xdebug.ini \
    && rm /usr/bin/docker-php-ext-enable \
    && apt-get purge -y php-dev php-pear

# Finally, expose Xdebug port
EXPOSE 9003
