<?php

namespace Modules\Online\Events;

use Illuminate\Queue\SerializesModels;

class CourseCompleted
{
    use SerializesModels;

    public $result;
    
    /**
     * Create a new event instance.
     * @param \Modules\Online\Entities\OnlineResult $result
     * @return void
     */
    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
