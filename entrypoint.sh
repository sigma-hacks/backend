#!/bin/bash

sudo chmod 755 -R storage
sudo chown -R sigma:sigma *
sudo chown -R sigma:sigma .*

composer install
composer dump-autoload

# start fpm
php-fpm
