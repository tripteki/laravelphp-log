<?php

namespace Tripteki\Log\Events\Logs;

use Illuminate\Queue\SerializesModels as SerializationTrait;
use Illuminate\Contracts\Auth\Authenticatable as AuthContract;
use Illuminate\Database\Eloquent\Model;

class Update
{
    use SerializationTrait;

    /**
     * @var string
     */
    public $event;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $object;

    /**
     * @param string $event
     * @param string $subject
     * @param \Illuminate\Database\Eloquent\Model $object
     * @return void
     */
    public function __construct($event, $subject, Model $object)
    {
        $this->event = $event;
        $this->subject = $subject;
        $this->object = $object;
    }
};
