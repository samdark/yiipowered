#!/usr/bin/env bash

git pull
composer install --no-dev --classmap-authoritative --no-interaction
./yii migrate --interactive=0
