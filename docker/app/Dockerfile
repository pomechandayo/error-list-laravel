
# どんなdockerイメージを利用して構築をするか
FROM php:7.4-apache

# 設定ファイルをdockerコンテナ内のPHP、Apacheに読み込ませる
ADD php.ini /usr/local/etc/php/
ADD 000-default.conf /etc/apache2/sites-enabled/

# Composerのインストール
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

# ミドルウェアインストール
RUN apt-get update \
&& apt-get install -y \
git \
zip \
unzip \
vim \
libpng-dev \
libpq-dev \
&& docker-php-ext-install pdo_mysql

# Laravelで必要になるmodRewriteを有効化する
RUN mv /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled
RUN /bin/sh -c a2enmod rewrite

RUN apt-get update

RUN apt-get install -y wget libjpeg-dev libfreetype6-dev
RUN apt-get install -y  libmagick++-dev \
libmagickwand-dev \
libpq-dev \
libfreetype6-dev \
libjpeg62-turbo-dev \
libpng-dev \
libwebp-dev \
libxpm-dev

RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

Run apt-get update && \
    apt-get install -y libmagickwand-dev && \
    pecl install imagick && \
    docker-php-ext-enable imagick

RUN curl -sL https://deb.nodesource.com/setup_11.x | bash -
RUN apt-get install -y nodejs
RUN npm install npm@latest -g


RUN echo "alias cl='cd laravelapp'" >> /root/.bashrc　
RUN echo "alias pa='php artisan'" >> /root/.bashrc
RUN echo "alias pam='php artisan migrate'" >> /root/.bashrc
RUN echo "alias pamrs='php artisan migrate:refresh --seed'" >> /root/.bashrc
RUN echo "alias pamrst='php artisan migrate:refresh --seed --env=testing'" >> /root/.bashrc
RUN echo "alias put='./vendor/bin/phpunit tests'" >> /root/.bashrc
RUN echo "alias putf='./vendor/bin/phpunit --filter'" >> /root/.bashrc