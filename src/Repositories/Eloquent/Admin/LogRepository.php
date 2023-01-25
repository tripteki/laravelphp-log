<?php

namespace Tripteki\Log\Repositories\Eloquent\Admin;

use Tripteki\Log\Contracts\Repository\Admin\ILogRepository;
use Tripteki\RequestResponseQuery\QueryBuilder;

class LogRepository implements ILogRepository
{
    /**
     * @param array $querystring|[]
     * @return mixed
     */
    public function all($querystring = [])
    {
        $querystringed =
        [
            "limit" => $querystring["limit"] ?? request()->query("limit", 10),
            "current_page" => $querystring["current_page"] ?? request()->query("current_page", 1),
        ];
        extract($querystringed);

        $field = "updated_at";
        $fields = [ "id", "causer_type", "causer_id", "subject_type", "subject_id", "log_name", "properties", "event", "created_at", "updated_at", ];

        $content = QueryBuilder::for(\Spatie\Activitylog\ActivitylogServiceProvider::getActivityModelInstance()->with([ "subject", "causer", ])->withTrashed())->
        defaultSort("-".$field)->
        allowedSorts($fields)->
        allowedFilters($fields)->
        paginate($limit, $fields, "current_page", $current_page)->appends(empty($querystring) ? request()->query() : $querystringed);

        return $content;
    }

    /**
     * @param int|string $identifier
     * @param array $querystring|[]
     * @return mixed
     */
    public function get($identifier, $querystring = [])
    {
        $content = \Spatie\Activitylog\ActivitylogServiceProvider::getActivityModelInstance()->withTrashed()->findOrFail($identifier);
        $content = $content->load([ "subject", "causer", ]);

        return $content;
    }
};
