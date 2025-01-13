# syntax=docker/dockerfile:1

FROM php:8.4.2-fpm-bullseye AS base

WORKDIR /app

ENV TZ=Asia/Tokyo \
  LANG=ja_JP.UTF-8 \
  LANGUAGE=ja_JP:ja \
  LC_ALL=ja_JP.UTF-8

ENV COMPOSER_HOME=/composer

ARG UID=1000
ARG GID=1000

COPY --from=composer:lts /usr/bin/composer /usr/bin/composer

RUN rm -f /etc/apt/apt.conf.d/docker-clean; echo 'Binary::apt::APT::Keep-Downloaded-Packages "true";' > /etc/apt/apt.conf.d/keep-cache
RUN \
    --mount=type=cache,target=/var/cache/apt,sharing=locked \
    --mount=type=cache,target=/var/lib/apt,sharing=locked \
    apt-get update && \
    apt-get -y install --no-install-recommends \
        locales \
        unzip \
        libzip-dev \
        libicu-dev \
        libonig-dev \
        openssl \
        postgresql \
        libpq-dev

RUN \
    locale-gen ja_JP.UTF-8 && \
    localedef -f UTF-8 -i ja_JP ja_JP.UTF-8 && \
    docker-php-ext-install \
        intl \
        pdo_pgsql \
        zip \
        bcmath && \
    groupadd --gid $GID phper && \
    useradd --uid $UID --gid $GID phper && \
    mkdir /composer && \
    mkdir -p /home/phper/.config/psysh && \
    chown phper:phper /composer && \
    chown phper:phper /app && \
    chown phper:phper /home/phper/.config/psysh

FROM base AS local

RUN \
  pecl install xdebug && \
  docker-php-ext-enable xdebug  && \
  mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

COPY ./docker/php/php.ini-development $PHP_INI_DIR/conf.d/docker-php.ini

#  Cannot install, php_dir for channel "pecl.php.net" is not writeable by the current user というエラーが発生するため、
#  xdebugのインストール完了後に実行USERを変更する
USER phper

#COPY "./docker/php/xdebug.ini" "$PHP_INI_DIR/conf.d/xdebug.ini"

# TODO: デプロイ用の設定を追加する
#FROM base AS deploy

#RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

#COPY ./docker/php/php.ini-production $PHP_INI_DIR/conf.d/docker-php.ini
#COPY --chown=phper:phper . /app

#USER phper

#RUN <<EOF
#  composer install --quiet --no-interaction --no-ansi --no-dev --no-scripts --no-progress --prefer-dist
#  composer dump-autoload --optimize
#  chmod -R 777 storage bootstrap/cache
#  php artisan optimize:clear
#  php artisan optimize
#EOF
