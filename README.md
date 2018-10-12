# laravel-bootstrap
Laravel Package to install the Bootstrap library (v3.3.7) and create a welcome page template.

## Requirements
[Laravel](https://laravel.com/docs/5.5) >= 5.5

## Package Installation
    composer require cdz/laravel-bootstrap
    composer update

## Install the Bootstrap library and create the welcome page template

    php artisan cdz-bootstrap:install
    
If you would like to overwrite existing files, use the --force switch:

    php artisan cdz-bootstrap:install --force
    
If you would like to only scaffold the views, use the --views switch (Controllers and routes will remain unchanged): 

    php artisan cdz-bootstrap:install --views
