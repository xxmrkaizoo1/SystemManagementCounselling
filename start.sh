#!/usr/bin/env sh
set -eu

cd /var/www/html
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

exec /usr/bin/supervisord -n -c /etc/supervisord.conf
