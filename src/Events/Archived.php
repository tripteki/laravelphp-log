<?php

namespace Tripteki\Log\Events;

use Illuminate\Queue\SerializesModels as SerializationTrait;

class Archived
{
    use SerializationTrait;

    /**
     * @var mixed
     */
    public $data;

    /**
     * @param mixed $data
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
};
