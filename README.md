

# Advertising Campaign

## Requirements

- PHP >= 7.3
- Laravel 8
- Database: MySql( 8.0.28), MariaDB(10.4.22) (will run with lower versions as well because only basic concepts)
- node js >= 12.13.0
- Composer 2

Sorry could not add docker because of facing some error while pulling images while building, then it crushes. 

## Environment Setup 

### Backend - laravel

- Go to `campaign_backend` directory and rename `.env.example` to `.env`
- Run `composer install`.
- In the `.env` file change the database connection info
```
    DB_CONNECTION=mysql 
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=campaigns
    DB_USERNAME=root
    DB_PASSWORD=
```
- Run migration `php artisan migrate` for migrating the database tables
- Run `php artisan db:seed --class=CampaignSeeder` for seeding the database
- Before serving change the `APP_URL` in which port or other url ( or ip) you are going to run in the `.env` file, if you don't change it, image path won't be found. ( example: `APP_URL=http://127.0.0.1:8000` )
- Then serve `php artisan serve` or `php artisan serve --port=9000` from `campaign_frontend`

### Frontend - React js

- Go to `campaign_frontend` directory and run `npm install`
- If you make changes in `APP_URL`, make the change in `/campaign_frontend/src/services/campaign.service.js` variable `url` putting `/api` in the end ( example: `const url = 'http://127.0.0.1:8000/api';` )
- then serve `npm start` from `campaign_frontend`

## Run Test

- run `./vendor/bin/phpunit` from `campaign_backend` to run the tests


