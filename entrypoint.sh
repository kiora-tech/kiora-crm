#!/bin/sh

php bin/console doctrine:migrations:migrate

# Fixer les permissions pour le dossier d'uploads
chown -R www-data:www-data /var/www/website/public/uploads
chmod -R 775 /var/www/website/public/uploads

php-fpm