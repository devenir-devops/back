FROM php:8.1.9-fpm-alpine3.16


RUN apk add --update --no-cache --virtual build-dependencies  zip zlib-dev libpng-dev autoconf  build-base openssl-dev icu-dev icu-data-full  \
    && docker-php-ext-configure gd \
    && docker-php-ext-install gd
RUN pecl install mongodb \
    && docker-php-ext-enable mongodb
RUN docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-configure intl && docker-php-ext-install intl
RUN apk add --update --no-cache curl-dev libzip-dev && docker-php-ext-install  bcmath curl zip

RUN echo "date.timezone=${PHP_TIMEZONE:-UTC}" > $PHP_INI_DIR/conf.d/date_timezone.ini


ENV COMPOSER_HOME /composer

ENV PATH /composer/vendor/bin:$PATH

# Setup the Composer installer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
  && php /tmp/composer-setup.php --install-dir=/usr/bin --filename=composer


RUN addgroup --gid 1000 www \
    && adduser --uid 1000 --shell /bin/bash --home /var/www/html --ingroup www --no-create-home --disabled-password  www \
    && chown -R www:www /var/www/html


USER www



COPY --chown=www:www . /var/www/html
WORKDIR /var/www/html

ENV APP_ENV=prod

RUN composer install -o -a --prefer-dist --no-progress  --no-interaction --no-dev