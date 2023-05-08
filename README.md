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
In .env change DB_HOST to db
```
docker compose build
docker compose up -d
```
Run if you need:
```
docker-compose exec laravel-app composer install
docker-compose exec laravel-app npm run build:wp
docker-compose exec laravel-app php artisan storage:link
```
To stop run
```
docker-compose stop
```
