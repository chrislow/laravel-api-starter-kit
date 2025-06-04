# What's different from the original laravel

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
8. Added the `tests/Feature/Http/Controllers/AuthControllerTest.php` file with tests for the auth routes.