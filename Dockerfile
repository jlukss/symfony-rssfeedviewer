FROM php:7.2-apache

RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - \
    && apt-get update \
    && apt-get install -y unzip wget git nodejs

RUN curl -sS https://getcomposer.org/installer | \
    php -- --install-dir=/usr/local/bin --filename=composer \
    ; wget https://get.symfony.com/cli/installer -O - | bash \
    && mv /root/.symfony/bin/symfony /usr/local/bin/symfony \
    ;

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"


COPY composer.* /var/www/html/
COPY package* /var/www/html/

RUN composer install --optimize-autoloader
RUN npm install

RUN rm -Rf /var/www/html/assets /var/www/html/src /var/www/html/config /var/www/html/.env \
    && echo "APP_ENV=prod" > /var/www/html/.env \
    && echo "APP_DEBUG=0" >> /var/www/html/.env 


COPY assets /var/www/html/assets
COPY src /var/www/html/src
COPY config /var/www/html/config
COPY data /var/www/html/data
COPY templates /var/www/html/templates
COPY webpack.config.js /var/www/html/
COPY symfony.lock /var/www/html/

RUN npm run build \
    ; chown www-data /var/www/html/data -Rf \
    ;

COPY public/.htaccess /var/www/html/public/

RUN APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
ENV APP_ENV prod
ENV APP_DEBUG 0
ENV APP_SECRET 962566936363551136ebb9551ba37151

RUN a2enmod rewrite

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf