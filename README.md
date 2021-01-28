
# Laravel More Command
A simple package for create __Repository, Repository with Interface, Service, Trait__ form command line using `php artisan` command.\
[Note : This package also worked for [nWidart/laravel-modules](https://github.com/nWidart/laravel-modules)]

## Installation
Require the package with composer using the following command:

```
composer require theanik/laravel-more-command --dev
```

Or add the following to your composer.json's require-dev section and `composer update`

```json
"require-dev": {
        "theanik/laravel-more-command": "^1.0.0"
    }
```
## Artisan Commands

## In Laravel Project

__Create a repository Class.__\
`php artisan make:repository your-repository-name`

Example:
```
php artisan make:repository UserRepository
```
or
```
php artisan make:repository Backend\UserRepository
```

The above will create a **Repositories** directory inside the **App** directory.\

__Create a repository with Interface.__\
`php artisan make:repository your-repository-name -i`

Example:
```
php artisan make:repository UserRepository -i
```
or
```
php artisan make:repository Backend\UserRepository -i
```
Here you need to put extra `-i` flag.
The above will create a **Repositories** directory inside the **App** directory.


__Create a Service Class.__\
`php artisan make:service your-service-name`

Example:
```
php artisan make:service UserService
```
or
```
php artisan make:service Backend\UserService
```
The above will create a **Services** directory inside the **App** directory.

__Create a Trait.__\
`php artisan make:trait your-trait-name`

Example:
```
php artisan make:trait HasAuth
```
or
```
php artisan make:trait Backend\HasAuth
```
The above will create a **Traits** directory inside the **App** directory.



## In [nWidart/laravel-modules](https://github.com/nWidart/laravel-modules) Modules

__Create a repository Class.__\
`php artisan module-make:repository your-repository-name {module-name}`

Example:
```
php artisan module-make:repository UserRepository Blog
```
or
```
php artisan make:repository Backend\UserRepository Blog
```

The above will create a **Repositories** directory inside the **{Module}** directory.

__Create a repository with Interface.__\
`php artisan make:repository your-repository-name {module-name} -i`

Example:
```
php artisan module-make:repository UserRepository -i Blog
```
or
```
php artisan module-make:repository Backend\UserRepository -i Blog
```
Here you need to put extra `-i` flag.
The above will create a **Repositories** directory inside the **{Module}** directory.


__Create a Service Class.__\
`php artisan module-make:service your-service-name {module-name}`

Example:
```
php artisan module-make:service UserService
```
or
```
php artisan module-make:service Backend\UserService
```
The above will create a **Services** directory inside the **{Module}** directory.

__Create a Trait.__\
`php artisan make:trait your-trait-name {module-name}`

Example:
```
php artisan module-make:trait HasAuth
```
or
```
php artisan module-make:trait Backend\HasAuth
```
The above will create a **Traits** directory inside the **{Module}** directory.



__An Example of created repository class:__

```
<?php

<?php

namespace App\Repositories;

class UserRepository
{
    public function __constuct()
    {
        //
    }
}


```

<a href="https://www.buymeacoffee.com/fMy8dmHGl" target="_blank"><img src="https://bmc-cdn.nyc3.digitaloceanspaces.com/BMC-button-images/custom_images/orange_img.png" alt="Buy Me A Coffee" style="height: auto !important;width: auto !important;" ></a>


