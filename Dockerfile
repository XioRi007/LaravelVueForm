FROM php:8.1-apache
RUN apt-get update \
    && apt-get install -y gnupg curl wget ca-certificates unzip lsb-release libssl-dev pkg-config supervisor \
    && docker-php-ext-install mysqli pdo pdo_mysql \
    && php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && chown -R www-data:www-data /var/www

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.log_level=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host = host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /app
COPY . /app
RUN chown -R www-data:www-data /app

RUN curl -sL https://deb.nodesource.com/setup_16.x | bash - \
 && apt-get install -y nodejs
RUN composer install
RUN npm install vite
RUN npm install
RUN npm run build
RUN php artisan storage:link


COPY vhost.conf /etc/apache2/sites-available/000-default.conf
EXPOSE 80

RUN a2enmod rewrite
