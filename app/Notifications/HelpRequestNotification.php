<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\HelpRequest;

class HelpRequestNotification extends Notification
{
    use Queueable;

    protected $helpRequest;
    protected $type;

    public function __construct(HelpRequest $helpRequest, $type = 'new')
    {
        $this->helpRequest = $helpRequest;
        $this->type = $type; // 'new' (untuk admin) atau 'updated' (untuk user)
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        if ($this->type === 'new') {
            return [
                'type' => 'App\Notifications\HelpRequestNotification',
                'title' => 'Laporan Bantuan Baru',
                'message' => 'Laporan masuk dari ' . $this->helpRequest->nama_pengirim,
                'help_request_id' => $this->helpRequest->id,
            ];
        } else {
            return [
                'type' => 'App\Notifications\HelpRequestNotification',
                'title' => 'Status Pelaporan Bantuan',
                'message' => 'Laporan kendala Anda: ' . ucfirst($this->helpRequest->status),
                'help_request_id' => $this->helpRequest->id,
            ];
        }
    }
}
