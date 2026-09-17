#!/bin/sh
sed -i "s/Listen .*/Listen ${PORT:-80}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:[0-9]*>/<VirtualHost *:${PORT:-80}>/" /etc/apache2/sites-available/000-default.conf
rm -f /var/www/html/bootstrap/cache/config.php
exec apache2-foreground
