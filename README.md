<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About assignment

To run the application you need to have docker installed on your machine.

&& run composer install

```bash
composer install
```

To run the application you need to run the following commands:

```bash
./vendor/bin/sail up -d && yarn install && yarn dev
```

To create test data you need to run command:

```bash
./vendor/bin/sail artisan db:seed --class=UsersSeeder
```

Assigment contain frontend part as well as backend part.

Front is on '/' route of the application.

Also there are routes for backend part:

```angular2html

DELETE     api/notifications/{notification}
GET        api/notifications/{user}
POST       api/notifications/{user}
GET        api/profiles/{profile}
PUT        api/profiles/{profile}
GET        api/users
GET        api/users/{user}
```
You can get full documentation using the collection postman\realtime-communication.json

