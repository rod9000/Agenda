#!/bin/sh
sed -i "s/listen 80;/listen ${PORT:-80};/" /etc/nginx/sites-available/default
rm -f /var/www/html/bootstrap/cache/config.php
php-fpm -D
nginx -g 'daemon off;'
