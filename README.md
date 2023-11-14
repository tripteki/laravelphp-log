<h1 align="center">Log</h1>

This package provides implementation of Auth Activity Log in repository pattern for Lumen and Laravel besides REST API starterpack of admin management with no intervention to codebase and keep clean.

Getting Started
---

Installation :

```
composer require tripteki/laravelphp-log
```

How to use it :

- Read detail optional instruction here [Log](https://spatie.be/docs/laravel-activitylog/v4/installation-and-setup).

- Put `Tripteki\Log\Traits\LogTrait` to any of your model loggable then optionally you can configure `protected static` of `$recordName`, `$recordEvents`, and `$recordLists`.

- Put `Tripteki\Log\Providers\LogServiceProvider` to service provider configuration list.

- Put `Tripteki\Log\Providers\LogServiceProvider::ignoreConfig()` into `register` provider, then publish config file into your project's directory with running :

```
php artisan vendor:publish --tag=tripteki-laravelphp-log
```

- Put `Tripteki\Log\Providers\LogServiceProvider::ignoreMigrations()` into `register` provider, then publish migrations file into your project's directory with running (optionally) :

```
php artisan vendor:publish --tag=tripteki-laravelphp-log-migrations
```

- Migrate.

```
php artisan migrate
```

- Publish tests file into your project's directory with running (optionally) :

```
php artisan vendor:publish --tag=tripteki-laravelphp-log-tests
```

- Sample :

```php
use Tripteki\Log\Contracts\Repository\Admin\ILogRepository as ILogAdminRepository;
use Tripteki\Log\Contracts\Repository\ILogRepository;

$logAdminRepository = app(ILogAdminRepository::class);

// $logAdminRepository->get(5); //
// $logAdminRepository->all(); //

$repository = app(ILogRepository::class);
// $repository->setUser(...); //
// $repository->getUser(); //

// $repository->archive(5); //
// $repository->unarchive(5); //
// $repository->get(5); //
// $repository->all(); //
```

- Generate swagger files into your project's directory with putting this into your annotation configuration (optionally) :

```
base_path("app/Http/Controllers/Log")
```

```
base_path("app/Http/Controllers/Admin/Log")
```

Usage
---

`php artisan adminer:install:log`

Author
---

- Trip Teknologi ([@tripteki](https://linkedin.com/company/tripteki))
- Hasby Maulana ([@hsbmaulana](https://linkedin.com/in/hsbmaulana))
