<?php

namespace Tripteki\Log\Traits;

use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

trait LogTrait
{
    use LogsActivity;

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
