#!/bin/bash
docker-compose exec app bash -c "composer install && php artisan migrate && php artisan test"
