#!/usr/bin/env sh

echo "Git fetch newest tag"
cd /var/storage/www/ESCOOTERS/
git fetch
TAG="$(git describe --tags --abbrev=0 origin/main)"
FILE="docker-compose.staging.yaml"

echo "Git checkout tag: " $TAG
git checkout tags/$TAG -f

echo "Execute composer and install packages"
docker-compose -f $FILE exec -T app composer install --optimize-autoloader

echo "Install npm assets"
docker-compose -f $FILE exec -T app npm install

echo "Build npm assets"
docker-compose -f $FILE exec -T app npm run build

echo "Run migrations and create caches"
docker-compose -f $FILE exec -T app php artisan migrate --force &&
    docker-compose -f $FILE exec -T app php artisan config:cache &&
    docker-compose -f $FILE exec -T app php artisan route:cache &&
    docker-compose -f $FILE exec -T app php artisan view:cache

echo "Restart app container"
docker-compose -f $FILE restart app
