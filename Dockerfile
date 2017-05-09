FROM php:7-fpm-alpine

MAINTAINER Nebo#15 support@nebo15.com

ENV HOME=/app

RUN apk add --no-cache --virtual .ext-deps \
        libwebp-dev \
        freetype-dev \
        libmcrypt-dev

RUN \
    docker-php-ext-configure mcrypt

RUN \
    apk add --no-cache --virtual .mongodb-ext-build-deps openssl-dev && \
    pecl install mongodb && \
    pecl clear-cache && \
    apk del .mongodb-ext-build-deps

RUN \
    docker-php-ext-install mcrypt && \
    docker-php-ext-enable mongodb.so && \
    docker-php-source delete

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer

# Setup project structure
RUN mkdir -p ${HOME}/storage/app && \
    mkdir ${HOME}/storage/logs && \
    mkdir -p ${HOME}/storage/framework/cache && \
    mkdir ${HOME}/storage/framework/sessions && \
    mkdir ${HOME}/storage/framework/views && \
    mkdir -p ${HOME}/public/dump

# Prefetch dependencies
COPY composer.* ${HOME}/

RUN composer --no-ansi --no-dev --no-interaction --no-progress --no-scripts --no-autoloader -d=${HOME} install

# Add project sources.
# To skip some files add them to .dockerignore file
COPY . ${HOME}/

# Install dependencies and generate autoloader
RUN composer --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader -d=${HOME} install

# Fix paths access rights
RUN chmod 777 -Rf ${HOME}/storage/ ${HOME}/public/dump/

RUN cp ${HOME}/.env.example ${HOME}/.env

CMD ["php-fpm", "-F"]