FROM nebo15/alpine-php:php-7.0

MAINTAINER Nebo #15 support@nebo15.com

ENV HOME=/app

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
