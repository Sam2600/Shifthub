#!/bin/bash

if [ -f vendor/autoload.php ]; then
    composer install
fi

if [ ! -f ".env" ]; then
    echo "Creating an env file for $APP_ENV"
    cp env.example .env
else
    echo "Env file is found."
fi


php artisan optimize:clear
php artisan key:generate

php artisan serve --port=$PORT --host=0.0.0.0 --env=.env

exec docker-php-entrypoint "$@"
