

## About 
This is my free backend admin system with base features like user, roles, permission, website configurations in laravel 11.

## Setup
- Clone the repo
- Setup .env file
- Hit 
`composer install`
`npm install`
`npm run dev`
`php artisan migrate`
`php artisan db:seed`
`php artisan serve`


## For File manager setup follow this steps.
- php artisan vendor:publish --tag=fm-config
- php artisan vendor:publish --tag=fm-assets
- or Follow this. https://www.webappfix.com/post/laravel-9-file-manager-integration-tutorial.html

## For GD Driver issue
- composer require intervention/image-laravel
- php artisan vendor:publish --provider="Intervention\Image\Laravel\ServiceProvider"
- add this code inside image.php
`return [
  'driver' => \Intervention\Image\Drivers\Gd\Driver::class,
  'options' => [
  'autoOrientation' => true,
  'decodeAnimation' => true,
  'blendingColor' => 'ffffff',
  ]
  ];`
- Follow this for more: https://github.com/Intervention/image-laravel
