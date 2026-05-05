FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html
ENV WEBROOT=/var/www/html/public
COPY . .

RUN composer install --no-dev --optimize-autoloader


CMD ["/start.sh"]
