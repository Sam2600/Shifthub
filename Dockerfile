FROM php:apache as php

WORKDIR /var/www/html

RUN apt-get update -y &&\
    apt-get install -y unzip git nodejs

RUN docker-php-ext-install pdo pdo_mysql mysqli

COPY --from=composer:2.3.5 /usr/bin/composer /usr/bin/composer

COPY . .

ENV PORT=8000
ENTRYPOINT [ "Docker/entrypoint.sh" ]
