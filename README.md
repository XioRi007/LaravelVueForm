# Laravel Vue Conference Form

## Installation

1. Clone repository
2. Copy .env.example to .env and change db credentials
3. Run
```
composer install
npm install
npm run build
php artisan storage:link
php artisan migrate --seed
```
If occurs the error during npm run build try the following
```
npm install vite
npm run build
```
