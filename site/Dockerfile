FROM php:7.2.19-apache
# isset: curl, json, mbstring, fileinfo, openssl, pcre, pdo_sqlite

# docker build -t kodeforce/phalcon:latest .

ENV PHALCON_VERSION=3.4.4
ENV PHALCON_DEVTOOLS_VERSION=3.4.2

RUN apt-get update \
    # apache
    && a2enmod rewrite \
    # Install various PHP extensions
    && docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install \
        opcache \
        gettext \
    && pecl install xdebug-2.7.2 \
    && docker-php-ext-enable xdebug \
    && pecl install igbinary-3.0.1 \
    && docker-php-ext-enable igbinary

RUN apt-get install -y dos2unix

COPY config/apache /etc/apache2
COPY data /var/www/html

RUN chmod -R 0777 /var/www/html/public 
RUN chmod -R 0777 /var/www/html/resources 

# cphalcon
RUN curl -LO https://github.com/phalcon/cphalcon/archive/v${PHALCON_VERSION}.tar.gz \
    && tar xzf v${PHALCON_VERSION}.tar.gz \
    && docker-php-ext-install ${PWD}/cphalcon-${PHALCON_VERSION}/build/php7/64bits \
    && rm -rf v${PHALCON_VERSION}.tar.gz cphalcon-${PHALCON_VERSION}

RUN find . -type f -print0 | xargs -0 dos2unix
RUN apt-get --purge remove -y dos2unix && rm -rf /var/lib/apt/lists/*