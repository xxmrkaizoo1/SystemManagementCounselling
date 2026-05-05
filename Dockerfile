FROM node:20-alpine AS assets

WORKDIR /var/www/html

COPY package*.json ./
COPY scripts ./scripts
RUN npm install

COPY resources ./resources
COPY vite.config.js ./
RUN npm run build \
    && ls -la public/build \
    && test -f public/build/manifest.json

FROM richarvey/nginx-php-fpm:latest

WORKDIR /var/www/html

ENV WEBROOT=/var/www/html/public

COPY . .
COPY --from=assets /var/www/html/public/build /var/www/html/public/build

RUN composer install --no-dev --optimize-autoloader \
    && chmod +x /var/www/html/start.sh

CMD ["/var/www/html/start.sh"]
