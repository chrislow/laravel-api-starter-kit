# Laravel 12 API Starter Kit
This is a starter kit for building a RESTful API using Laravel 12 with Sanctum for authentication, PEST for testing, and Scribe for API documentation.

## Features
- **Laravel 12**: The latest version of Laravel.
- **Sanctum**: Simple token-based authentication for SPAs (Single Page Applications) and simple APIs.
- **PEST**: A modern PHP testing framework that makes writing tests enjoyable.
- **Scribe**: A package for generating API documentation from your Laravel routes and controllers.
- **Versioned API**: The API routes are versioned to allow for future changes without breaking existing clients.
- **All Changes documented**: The changes made to the original Laravel application are documented for clarity.
- **Near zero dependencies**: The starter kit is lightweight and does not include unnecessary packages, making it easy to extend and customize.

## What's different from the original laravel

1. Installed a fresh laravel 12 application by running the `laravel new` (Laravel Installer 5.14.0) command.
2. Added Sanctum with `php artisan install:api` command. See [Laravel Sanctum Docs](https://laravel.com/docs/12.x/sanctum).
3. Added `Laravel\Sanctum\HasApiTokens` trait to the `App\Models\User` model.
4. Removed the routes for the web application in `routes/web.php`.
5. Added the AuthController with `php artisan make:controller AuthController` command and implemented the following methods:
   - `login`
   - `logout`
   - `register`
   - `user`
6. Added the `api.php` routes file (versioned) with the following routes:
   ```php
   POST       api/v1/auth/login .............................................. v1.auth.login › AuthController@login
   POST       api/v1/auth/logout ........................................... v1.auth.logout › AuthController@logout
   POST       api/v1/auth/register ..................................... v1.auth.register › AuthController@register
   GET         api/v1/auth/user ................................................. v1.auth.user › AuthController@user
   ```
7. Made PEST the default testing framework by running the following commands:
   ```bash
   $ composer remove phpunit/phpunit
   $ composer require pestphp/pest --dev --with-all-dependencies
   $ ./vendor/bin/pest --init
   ```
8. Added the `tests/Feature/Http/Controllers/AuthControllerTest.php` file with tests for the auth routes
9. Added Scribe with `composer require knuckleswtf/scribe` command, using default settings to generate API documentation.