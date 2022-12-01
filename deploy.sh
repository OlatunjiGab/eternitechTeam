#!/bin/bash

sudo git pull
sudo chown -R www-data:www-data /var/www/myapp
docker-compose exec app php artisan deploy
docker-compose up -d --force-recreate --no-deps app supervisor 
