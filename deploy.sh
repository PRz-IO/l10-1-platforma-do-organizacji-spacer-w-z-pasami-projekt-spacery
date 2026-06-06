#!/bin/bash

# Tworzenie brakujących folderów cache
mkdir -p /home/site/wwwroot/storage/framework/views
mkdir -p /home/site/wwwroot/storage/framework/cache
mkdir -p /home/site/wwwroot/storage/framework/sessions

# Nadawanie uprawnień
chmod -R 777 /home/site/wwwroot/storage
chmod -R 777 /home/site/wwwroot/bootstrap/cache

# Czyszczenie cache Laravela
php /home/site/wwwroot/artisan config:clear
php /home/site/wwwroot/artisan cache:clear
php /home/site/wwwroot/artisan view:clear

if [ ! -L /home/site/wwwroot/public/storage ]; then
    php /home/site/wwwroot/artisan storage:link
fi