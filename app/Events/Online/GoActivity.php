<?php

namespace App\Events\Online;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GoActivity
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $course_id;
    public $course_activity_id;
    
    public function __construct($course_id, $course_activity_id)
    {
        $this->course_id = $course_id;
        $this->course_activity_id = $course_activity_id;
    }
    
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
