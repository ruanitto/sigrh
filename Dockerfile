FROM php:7.2-apache

RUN apt-get update \
  && apt-get install -y git zlib1g-dev libldap2-dev libicu-dev \
  && docker-php-ext-install zip \
  && docker-php-ext-install intl \
  && docker-php-ext-install ldap \
  && a2enmod rewrite \
  && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
  && mv /var/www/html /var/www/public 
#  && curl -sS https://getcomposer.org/installer \
#   | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./composer.phar /usr/local/bin/composer

RUN chmod +x /usr/local/bin/composer

WORKDIR /var/www
