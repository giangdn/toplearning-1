<?php

namespace Modules\CourseOld\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImportFailed extends Notification
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
            'title' => 'Lỗi import',
            'messages' => $this->messages,
            'status' => 'error',
        ];
    }
}
