FROM php:7.2-fpm

MAINTAINER MComScience <m-com3@hotmail.com>

# install ODBC driver
RUN apt-get update && apt-get install -y freetds-bin freetds-dev freetds-common libct4 libsybdb5 libicu-dev libcurl3-dev git zlib1g-dev apt-transport-https gnupg wget

# Install mssql drivers
RUN apt-get install -y unixodbc-dev
RUN curl https://packages.microsoft.com/keys/microsoft.asc | apt-key add -
RUN curl https://packages.microsoft.com/config/debian/9/prod.list > /etc/apt/sources.list.d/mssql-release.list
RUN apt-get update -y
RUN apt-get upgrade -y
RUN export DEBIAN_FRONTEND=noninteractive && ACCEPT_EULA=Y apt-get install -y msodbcsql17 mssql-tools
RUN ln -fsv /opt/mssql-tools/bin/* /usr/bin

RUN apt-get update && apt-get install -y autoconf tzdata openntpd file g++ git gcc binutils libc-dev musl-dev make re2c  coreutils libmcrypt-dev libpng-dev libxml2-dev libcurl4-openssl-dev curl \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libssl-dev

RUN docker-php-ext-configure intl --enable-intl \
    && docker-php-ext-install -j$(nproc) iconv mysqli pdo pdo_mysql curl bcmath mbstring json xml zip opcache intl \
    && pecl install sqlsrv \
    && pecl install pdo_sqlsrv-5.2.0 \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

# TimeZone
RUN cp /usr/share/zoneinfo/Asia/Bangkok /etc/localtime \
    && echo "Asia/Bangkok" >  /etc/timezone

# Install Composer && Assets Plugin
RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer global require --no-progress "fxp/composer-asset-plugin:~1.4" \
    #&& apk del tzdata \
    && rm -rf /var/cache/apk/*

EXPOSE 9000

CMD ["php-fpm"]

