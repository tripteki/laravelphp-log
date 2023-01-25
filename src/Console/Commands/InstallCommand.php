<?php

namespace Tripteki\Log\Console\Commands;

use Tripteki\Helpers\Contracts\AuthModelContract;
use Tripteki\Helpers\Helpers\ProjectHelper;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = "adminer:install:log";

    /**
     * @var string
     */
    protected $description = "Install the log stack";

    /**
     * @var \Tripteki\Helpers\Helpers\ProjectHelper
     */
    protected $helper;

    /**
     * @param \Tripteki\Helpers\Helpers\ProjectHelper $helper
     * @return void
     */
    public function __construct(ProjectHelper $helper)
    {
        parent::__construct();

        $this->helper = $helper;
    }

    /**
     * @return int
     */
    public function handle()
    {
        $this->installStack();

        return 0;
    }

    /**
     * @return int|null
     */
    protected function installStack()
    {
        (new Filesystem)->ensureDirectoryExists(base_path("routes/user"));
        (new Filesystem)->ensureDirectoryExists(base_path("routes/admin"));
        (new Filesystem)->copy(__DIR__."/../../../stubs/routes/user/log.php", base_path("routes/user/log.php"));
        (new Filesystem)->copy(__DIR__."/../../../stubs/routes/admin/log.php", base_path("routes/admin/log.php"));
        $this->helper->putRoute("api.php", "user/log.php");
        $this->helper->putRoute("api.php", "admin/log.php");

        (new Filesystem)->ensureDirectoryExists(app_path("Http/Controllers/Log"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Controllers/Log", app_path("Http/Controllers/Log"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Requests/Logs"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Requests/Logs", app_path("Http/Requests/Logs"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Controllers/Admin/Log"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Controllers/Admin/Log", app_path("Http/Controllers/Admin/Log"));
        (new Filesystem)->ensureDirectoryExists(app_path("Imports/Logs"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Imports/Logs", app_path("Imports/Logs"));
        (new Filesystem)->ensureDirectoryExists(app_path("Exports/Logs"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Exports/Logs", app_path("Exports/Logs"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Requests/Admin/Logs"));
        (new Filesystem)->copyDirectory(__DIR__."/../../../stubs/app/Http/Requests/Admin/Logs", app_path("Http/Requests/Admin/Logs"));
        (new Filesystem)->ensureDirectoryExists(app_path("Http/Responses"));

        $this->helper->putTrait($this->helper->classToFile(get_class(app(AuthModelContract::class))), \Tripteki\Log\Traits\LogCauseTrait::class);

        $this->info("Adminer Log scaffolding installed successfully.");
    }
};
