<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\ApprovalRequest;

class ApprovalRequestNotification extends Notification
{
    use Queueable;

    protected $approvalRequest;
    protected $type;

    public function __construct(ApprovalRequest $approvalRequest, $type = 'new')
    {
        $this->approvalRequest = $approvalRequest;
        $this->type = $type;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $messages = [
            'new' => 'Permintaan persetujuan baru dari ' . $this->approvalRequest->user->name,
            'approved' => 'Permintaan persetujuan Anda telah disetujui',
            'rejected' => 'Permintaan persetujuan Anda telah ditolak'
        ];

        return [
            'approval_request_id' => $this->approvalRequest->id,
            'message' => $messages[$this->type],
            'type' => $this->type,
            'notes' => $this->approvalRequest->notes
        ];
    }
} 