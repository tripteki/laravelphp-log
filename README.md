<h1 align="center">Log</h1>

This package provides wrapper of an implementation Auth Activity Log in repository pattern for Lumen and Laravel.

Getting Started
---

Installation :

```
$ composer require tripteki/laravelphp-log
```

How to use it :

- Put `Tripteki\Log\Providers\LogServiceProvider` to service provider configuration list.

- Publish config file in the root of your project's directory with running and put to register provider :

```
php artisan vendor:publish --tag=tripteki-laravelphp-log
```

```php
Tripteki\Log\Providers\LogServiceProvider::ignoreConfig();
```

- Put `Tripteki\Log\Traits\LogCauseTrait` to auth's provider model.

- Put `Tripteki\Log\Traits\LogTrait` to any of your model's target and optionally you can configure `protected static` of `$recordName`, `$recordEvents`, and `$recordLists`.

- Do activities to your models' target.

- Migrate.

```
$ php artisan migrate
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

Author
---

- Spatie ([@spatie](https://spatie.be))
- Trip Teknologi ([@tripteki](https://linkedin.com/company/tripteki))
- Hasby Maulana ([@hsbmaulana](https://linkedin.com/in/hsbmaulana))
