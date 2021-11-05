<?php

namespace App\Events;

use \Modules\Messages\Entities\Message;
use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageUser implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $message;
    public $user;
    public $socketId;
    public $type;
    public function __construct(Message $message, User $user, $socketId,$bot=0)
    {
        $this->message = $message;
        $this->user = $user;
        $this->socketId = $socketId;
        $this->type  = $bot;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */

    public function broadcastOn()
    {
        return new PrivateChannel('chat.'.$this->user->id);
//        return ['messageuser'];
        // hoặc: return new Channel('chatroom');
    }
}
