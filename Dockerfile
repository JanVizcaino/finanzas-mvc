FROM php:8.1-apache

RUN apt-get update && apt-get install -y libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql

RUN a2enmod rewrite

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN mkdir -p /var/www/html/logs
RUN chown -R www-data:www-data /var/www/html/logs
RUN chmod -R 775 /var/www/html/logs