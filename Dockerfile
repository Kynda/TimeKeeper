FROM php:8.0-apache
MAINTAINER Hallenbeck Digital Construction

RUN apt update && apt install -y --no-install-recommends \
        unzip \
        zip \
        git \
        vim \
        zsh \
        tmux \
        rsync \
        sqlite3 && \
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug && \
    touch /var/log/xdebug.log && chmod 666 /var/log/xdebug.log && \
    a2enmod rewrite
