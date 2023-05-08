# Laravel Vue Conference Form

## Installation

1. Clone repository
2. Copy .env.example to .env and change db credentials
3. Run
```
composer install
npm install
npm run build:wp
php artisan storage:link
php artisan migrate --seed
```
If occurs the error during npm run build try the following
```
npm install vite
npm run build
```
Admin credentials is 
admin@email.com
password

## Docker
```
docker compose build
docker compose up -d
```
If it is the first time run:
```
docker-compose exec laravel-app php artisan migrate --seed
```
To stop run
```
docker-compose stop
```
