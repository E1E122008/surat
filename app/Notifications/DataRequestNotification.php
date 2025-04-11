<?php

namespace App\Notifications;

use App\Models\ApprovalRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DataRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $approvalRequest;

    public function __construct(ApprovalRequest $approvalRequest)
    {
        $this->approvalRequest = $approvalRequest;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        $message = '';
        $type = '';

        if ($this->approvalRequest->status == 'pending') {
            $message = 'Ada permintaan baru untuk menambah data ' . $this->getDataTypeText() . ' dari ' . $this->approvalRequest->user->name;
            $type = 'info';
        } elseif ($this->approvalRequest->status == 'approved') {
            $message = 'Permintaan Anda untuk menambah data ' . $this->getDataTypeText() . ' telah disetujui';
            $type = 'success';
        } else {
            $message = 'Permintaan Anda untuk menambah data ' . $this->getDataTypeText() . ' ditolak';
            $type = 'error';
        }

        return [
            'message' => $message,
            'type' => $type,
            'approval_request_id' => $this->approvalRequest->id,
        ];
    }

    protected function getDataTypeText()
    {
        switch ($this->approvalRequest->letter_type) {
            case 'surat_masuk':
                return 'Surat Masuk';
            case 'surat_keluar':
                return 'Surat Keluar';
            case 'sk':
                return 'SK';
            case 'perda':
                return 'PERDA';
            case 'pergub':
                return 'PERGUB';
            case 'sppd_dalam':
                return 'SPPD Dalam Daerah';
            case 'sppd_luar':
                return 'SPPD Luar Daerah';
            case 'spt_dalam':
                return 'SPT Dalam Daerah';
            case 'spt_luar':
                return 'SPT Luar Daerah';
            default:
                return '';
        }
    }
} 