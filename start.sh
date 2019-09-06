#!/usr/bin/env bash

cd laradock

docker-compose up -d mysql nginx
docker-compose exec -u laradock workspace php artisan migrate
