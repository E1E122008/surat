<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SecurityAlertNotification extends Notification
{
    use Queueable;

    protected $userName;
    protected $url;

    public function __construct($userName, $url)
    {
        $this->userName = $userName;
        $this->url = $url;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Terdapat indikasi pelanggaran bypass akses dari pengguna ' . $this->userName,
            'type' => 'security',
            'notes' => $this->url
        ];
    }
}
