<?php

namespace Tripteki\Log\Providers;

use Tripteki\Log\Models\Admin\Log;
use Tripteki\Uid\Observers\UniqueIdObserver;
use Tripteki\Log\Console\Commands\InstallCommand;
use Tripteki\Repository\Providers\RepositoryServiceProvider as ServiceProvider;

class LogServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $repositories =
    [
        \Tripteki\Log\Contracts\Repository\ILogRepository::class => \Tripteki\Log\Repositories\Eloquent\LogRepository::class,
        \Tripteki\Log\Contracts\Repository\Admin\ILogRepository::class => \Tripteki\Log\Repositories\Eloquent\Admin\LogRepository::class,
    ];

    /**
     * @var bool
     */
    public static $subjectUid = true;

    /**
     * @var bool
     */
    public static $loadConfig = true;

    /**
     * @var bool
     */
    public static $runsMigrations = true;

    /**
     * @return bool
     */
    public static function shouldSubjectUid()
    {
        return static::$subjectUid;
    }

    /**
     * @return bool
     */
    public static function shouldLoadConfig()
    {
        return static::$loadConfig;
    }

    /**
     * @return bool
     */
    public static function shouldRunMigrations()
    {
        return static::$runsMigrations;
    }

    /**
     * @return void
     */
    public static function ignoreSubjectUid()
    {
        static::$subjectUid = false;
    }

    /**
     * @return void
     */
    public static function ignoreConfig()
    {
        static::$loadConfig = false;
    }

    /**
     * @return void
     */
    public static function ignoreMigrations()
    {
        static::$runsMigrations = false;
    }

    /**
     * @return void
     */
    public function boot()
    {
        parent::boot();

        $this->dataEventListener();

        $this->registerPublishers();
        $this->registerConfigs();
        $this->registerCommands();
        $this->registerMigrations();
    }

    /**
     * @return void
     */
    protected function registerConfigs()
    {
        if (static::shouldLoadConfig()) {

            $this->app["config"]->set("activitylog", []);
            $this->mergeConfigFrom(__DIR__."/../../config/log.php", "activitylog");
        }
    }

    /**
     * @return void
     */
    protected function registerCommands()
    {
        if (! $this->app->isProduction() && $this->app->runningInConsole()) {

            $this->commands(
            [
                InstallCommand::class,
            ]);
        }
    }

    /**
     * @return void
     */
    protected function registerMigrations()
    {
        if ($this->app->runningInConsole() && static::shouldRunMigrations()) {

            $this->loadMigrationsFrom(__DIR__."/../../database/migrations");
        }
    }

    /**
     * @return void
     */
    protected function registerPublishers()
    {
        $this->publishes(
        [
            __DIR__."/../../config/log.php" => config_path("activitylog.php"),
        ],

        "tripteki-laravelphp-log");

        if (! static::shouldRunMigrations()) {

            $this->publishes(
            [
                __DIR__."/../../database/migrations" => database_path("migrations"),
            ],

            "tripteki-laravelphp-log-migrations");
        }

        $this->publishes(
        [
            __DIR__."/../../stubs/tests/Feature/Log/LogTest.stub" => base_path("tests/Feature/Log/LogTest.php"),
        ],

        "tripteki-laravelphp-log-tests");
    }

    /**
     * @return void
     */
    public function dataEventListener()
    {
        Log::observe(UniqueIdObserver::class);
    }
};
