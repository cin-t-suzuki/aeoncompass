FROM php:8.1.7-apache

COPY ./docker/app/php.ini /usr/local/etc/php/
COPY ./docker/app/000-default.conf /etc/apache2/sites-enabled/

# Composerのインストール
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

# ミドルウェアのインストール
RUN apt-get update \
&& apt-get install -y \
git \
libpng-dev \
libpq-dev \
unzip \
vim \
zip \
&& docker-php-ext-install pdo_mysql

RUN bash -c '[[ -n "$(pecl list | grep xdebug)" ]]\
  || (pecl install xdebug && docker-php-ext-enable xdebug)'

# Laravelで必要になるmodRewriteを有効化する
RUN mv /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled
RUN /bin/sh -c a2enmod rewrite

