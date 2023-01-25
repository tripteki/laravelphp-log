<?php

namespace Tripteki\Log\Listeners\Logs;

use Error;
use Exception;
use Tripteki\Log\Events\Logs\Any;
use Spatie\Activitylog\EventLogBag;
use Spatie\Activitylog\ActivityLogger;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue as QueueableContract;
use Illuminate\Queue\InteractsWithQueue as QueueInteractionTrait;

class AnyListener implements QueueableContract
{
    use QueueInteractionTrait;

    /**
     * @var bool
     */
    public $afterCommit = true;

    /**
     * @param \Tripteki\Log\Events\Any $event
     * @return void
     */
    public function handle(Any $event)
    {
        DB::beginTransaction();

        try {

            $event->object->activitylogOptions = $event->object->getActivitylogOptions();

            if (! $event->object->shouldLogEvent($event->event)) return;

            $changes = $event->object->attributeValuesToBeLogged($event->event);
            $description = $event->object->getDescriptionForEvent($event->event);
            $log = $event->object->getLogNameToUse();

            if ($description == "") return;
            if ($event->object->isLogEmpty($changes) && ! $event->object->activitylogOptions->submitEmptyLogs) return;

            /**
             * Pipeline.
             */
            $event = app(Pipeline::class)
            ->send(new EventLogBag($event->event, $event->object, $changes, $event->object->activitylogOptions))
            ->through(call_user_func($event->subject."::"."changesPipes"))
            ->thenReturn();

            /**
             * Log.
             */
            $logger = app(ActivityLogger::class)
            ->useLog($log)
            ->event($event->event)
            ->performedOn($event->object)
            ->withProperties($event->changes);

            if (method_exists($event->object, "tapActivity")) $logger->tap([ $event->object, "tapActivity", ], $event->event);

            $logger->log($description);

            /**
             * Transaction.
             */
            $event->object->activitylogOptions = null;
            $event->object->save();

            DB::commit();

        } catch (Exception $exception) {

            DB::rollback();
        }
    }
};
