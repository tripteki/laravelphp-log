<?php

namespace Tripteki\Log\Repositories\Eloquent;

use Error;
use Exception;
use Illuminate\Support\Facades\DB;
use Tripteki\Repository\AbstractRepository;
use Tripteki\Log\Events\Archiving;
use Tripteki\Log\Events\Archived;
use Tripteki\Log\Events\Unarchiving;
use Tripteki\Log\Events\Unarchived;
use Tripteki\Log\Contracts\Repository\ILogRepository;
use Tripteki\RequestResponseQuery\QueryBuilder;

class LogRepository extends AbstractRepository implements ILogRepository
{
    /**
     * @param array $querystring|[]
     * @return mixed
     */
    public function all($querystring = [])
    {
        $querystringed =
        [
            "type" => $querystring["type"] ?? request()->query("type", "unarchived"),
            "limit" => $querystring["limit"] ?? request()->query("limit", 10),
            "current_page" => $querystring["current_page"] ?? request()->query("current_page", 1),
        ];
        extract($querystringed);

        $content = $this->user;
        $action = $content->actions();

        if ($type == "archived") {

            $action = $action->onlyTrashed();
        }

        $field = "updated_at";
        $fields = [ "id", "subject_type", "subject_id", "log_name", "properties", "event", "created_at", "updated_at", ];

        $content = $content->setRelation("actions",
            QueryBuilder::for($action)->
            defaultSort("-".$field)->
            allowedSorts($fields)->
            allowedFilters($fields)->
            paginate($limit, $fields, "current_page", $current_page)->appends(empty($querystring) ? request()->query() : $querystringed));
        $content = $content->loadCount("actions");

        return collect($content)->only([ "actions_count", "actions", ]);
    }

    /**
     * @param int|string $identifier
     * @param array $querystring|[]
     * @return mixed
     */
    public function get($identifier, $querystring = [])
    {
        $content = $this->user->actions()->findOrFail($identifier);

        return $content;
    }

    /**
     * @param int|string $identifier
     * @return mixed
     */
    public function delete($identifier)
    {
        $content = $this->user->actions()->findOrFail($identifier);

        DB::beginTransaction();

        try {

            $content->delete();

            DB::commit();

        } catch (Exception $exception) {

            DB::rollback();
        }

        return $content;
    }

    /**
     * @param int|string $identifier
     * @return mixed
     */
    public function archive($identifier)
    {
        $content = $this->delete($identifier);

        event(new Archived($content));

        return $content;
    }

    /**
     * @param int|string $identifier
     * @return mixed
     */
    public function unarchive($identifier)
    {
        $content = $this->user->actions()->withTrashed()->findOrFail($identifier);

        DB::beginTransaction();

        try {

            $content->restore();

            DB::commit();

            event(new Unarchived($content));

        } catch (Exception $exception) {

            DB::rollback();
        }

        return $content;
    }
};
