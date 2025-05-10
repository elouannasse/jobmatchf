<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminNotification extends Notification
{
    use Queueable;

    protected $data;
    protected $type;

    
    public function __construct($type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
            'created_at' => now()->toDateTimeString()
        ];
    }
}