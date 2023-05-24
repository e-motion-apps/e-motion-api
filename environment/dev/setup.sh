#!/bin/sh
set -e

if [ ! -f ".env" ]; then
  cp .env.example .env
fi

docker-compose build --no-cache --pull &&
docker-compose up -d &&
docker-compose exec php composer install &&
docker-compose exec php php artisan key:generate &&
docker-compose exec php php artisan migrate --seed &&
docker-compose exec node npm install &&
docker-compose exec node npm run build
