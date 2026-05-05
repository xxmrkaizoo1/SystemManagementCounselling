#!/usr/bin/env sh
set -eu

cd /var/www/html

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwx storage bootstrap/cache

for conf in \
  /etc/nginx/sites-enabled/default.conf \
  /etc/nginx/conf.d/default.conf \
  /etc/nginx/sites-enabled/*.conf \
  /etc/nginx/conf.d/*.conf; do
  [ -f "$conf" ] || continue
  sed -i 's#root /var/www/html;#root /var/www/html/public;#g' "$conf" || true
  sed -i 's#root /usr/share/nginx/html;#root /var/www/html/public;#g' "$conf" || true
done

exec /usr/bin/supervisord -n -c /etc/supervisord.conf
