<?php

namespace Tripteki\Log\Models\Admin;

use Tripteki\Uid\Traits\UniqueIdTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Activity as Model;

class Log extends Model
{
    use SoftDeletes, UniqueIdTrait;
};
