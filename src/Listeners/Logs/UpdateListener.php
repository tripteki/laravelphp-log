<?php

namespace Tripteki\Log\Listeners\Logs;

use Error;
use Exception;
use Tripteki\Log\Events\Logs\Update;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Queue\ShouldQueue as QueueableContract;
use Illuminate\Queue\InteractsWithQueue as QueueInteractionTrait;

class UpdateListener implements QueueableContract
{
    use QueueInteractionTrait;

    /**
     * @var bool
     */
    public $afterCommit = true;

    /**
     * @param \Tripteki\Log\Events\Update $event
     * @return void
     */
    public function handle(Update $event)
    {
        DB::beginTransaction();

        try {

            $event->object->oldAttributes = call_user_func($event->subject."::"."logChanges", app($event->subject)->setRawAttributes($object->getRawOriginal()));
            $event->object->save();

            DB::commit();

        } catch (Exception $exception) {

            DB::rollback();
        }
    }
};
