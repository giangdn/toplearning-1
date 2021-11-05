<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImportUnitHasFailed extends Notification
{
    use Queueable;
    
    protected $messages;
    
    public function __construct($messages)
    {
        $this->messages = $messages;
    }
    
    public function via($notifiable)
    {
        return ['database'];
    }
    
    public function toArray($notifiable)
    {
        return [
            'title' => 'Lỗi import Tập đoàn',
            'messages' => $this->messages,
            'status' => 'error',
        ];
    }
}
