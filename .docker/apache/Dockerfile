FROM php:8.1.10-apache-bullseye

RUN echo "date.timezone=${PHP_TIMEZONE:-UTC}" > $PHP_INI_DIR/conf.d/date_timezone.ini

RUN apt-get update && apt-get -y install libcurl4-openssl-dev libzip-dev libicu-dev libssl-dev pkg-config \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-install  bcmath curl zip \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && docker-php-ext-configure intl && docker-php-ext-install intl \
    && docker-php-ext-configure opcache --enable-opcache && docker-php-ext-install opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

ENV COMPOSER_HOME /composer
ENV PATH /composer/vendor/bin:$PATH

# Setup the Composer installer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
  && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
  && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
  && php /tmp/composer-setup.php --install-dir=/usr/bin --filename=composer \
  && rm -f /tmp/composer-setup.php && rm -f /tmp/composer-setup.sig


RUN addgroup --gid 1000 www \
    && adduser --uid 1000 --shell /bin/bash --home /var/www/html --ingroup www --no-create-home --disabled-password  www \
    && chown -R www:www /var/www/html

ADD .docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
ADD .docker/apache/ports.conf /etc/apache2/ports.conf
RUN echo 'SetEnv MONGODB_URL ${MONGODB_URL}' > /etc/apache2/conf-enabled/environment.conf \
    && echo 'SetEnv MONGODB_DB ${MONGODB_DB}' >> /etc/apache2/conf-enabled/environment.conf \
    && echo 'SetEnv APP_ENV ${APP_ENV}' >> /etc/apache2/conf-enabled/environment.conf

RUN a2enmod rewrite && a2enmod headers && apache2ctl -t

USER www

COPY --chown=www:www . /var/www/html
WORKDIR /var/www/html

ENV APP_ENV=prod
RUN composer install -o -a --prefer-dist --no-progress  --no-interaction --no-dev

