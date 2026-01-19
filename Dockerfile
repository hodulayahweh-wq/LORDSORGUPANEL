FROM php:8.1-apache

ENV PORT=10000

RUN sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf \
 && sed -i "s/80/${PORT}/g" /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 10000
