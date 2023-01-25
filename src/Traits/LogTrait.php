<?php

namespace Tripteki\Log\Traits;

use Tripteki\Log\Events\Logs\Update;
use Tripteki\Log\Events\Logs\Any;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;

trait LogTrait
{
    use LogsActivity;

    /**
     * @return void
     */
    protected static function bootLogsActivity(): void
    {
        $class = static::class;

        static::eventsToBeRecorded()->each(function ($event) use ($class) {

            if ($event === "updated") {

                static::updating(function (Model $model) use ($event, $class) {

                    event(new Update($event, $class, $model));
                });
            }

            static::$event(function (Model $model) use ($event, $class) {

                event(new Any($event, $class, $model));
            });
        });
    }

    /**
     * @return array
     */
    public static function changesPipes(): array
    {
        return static::$changesPipes;
    }

    /**
     * @return \Spatie\Activitylog\LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        $log = LogOptions::defaults()
        ->logOnlyDirty()
        ->useLogName(isset(static::$recordName) ? static::$recordName : get_class($this));

        if (isset(static::$recordLists)) {

            $log = $log->logOnly(static::$recordLists);

        } else {

            $log = $log->logFillable();
        }

        return $log;
    }
};
