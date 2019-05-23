FROM php:7.2.16-apache

ENV DEBIAN_FRONTEND noninteractive

ENV HTTPD_PREFIX /usr/local/apache2
ENV PATH $HTTPD_PREFIX/bin:$PATH
RUN mkdir -p "$HTTPD_PREFIX" \
    && chown www-data:www-data "$HTTPD_PREFIX"
WORKDIR $HTTPD_PREFIX

RUN yes | pecl install xdebug-2.6.1
RUN echo "file_uploads=On" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "memory_limit=1024M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "upload_max_filesize=64M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "post_max_size=64M" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_execution_time=180" >> /usr/local/etc/php/conf.d/uploads.ini \
 && echo "max_input_vars=10000" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "error_reporting-1" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "display_errors = On" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "log_errors = On" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "error_log = /var/wwww/html/logs/php_error.log" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "date.timezone = \"Europe/Madrid\"" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "enable_dl=Off" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "allow_url_fopen=On" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "allow_url_include=Off" >> /usr/local/etc/php/conf.d/custom.ini \
 && echo "default_socket_timeout=60" >> /usr/local/etc/php/conf.d/custom.ini

RUN apt-get update \
 && apt-get install -y zlib1g-dev locales libpng-dev libfreetype6-dev libjpeg62-turbo-dev git unzip httrack libmcrypt-dev libxml++2.6-dev

RUN apt-get install -y vim

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
RUN docker-php-ext-install gettext
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
 && docker-php-ext-install gd
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install soap
RUN docker-php-ext-install exif
RUN docker-php-ext-install mbstring

RUN a2enmod proxy_fcgi ssl rewrite proxy proxy_balancer proxy_http proxy_ajp
RUN a2enmod headers
RUN service apache2 restart

RUN apt-get install -y wget

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer --version