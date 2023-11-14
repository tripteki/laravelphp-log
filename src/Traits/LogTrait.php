<?php

namespace Tripteki\Log\Traits;

use Illuminate\Support\Str;
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
        ->useLogName(isset(static::$recordName) ? static::$recordName : Str::beforeLast(get_class($this), "\\"))
        ->setDescriptionForEvent(function ($event) {

            return get_class($this).".".$event.".".$this->{$this->getKeyName()};
        });

        if (isset(static::$recordLists)) {

            $log = $log->logOnly(static::$recordLists);

        } else {

            $log = $log->logFillable();
        }

        return $log;
    }
};
