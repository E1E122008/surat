<?php

namespace App\Notifications;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class SecurityAlertNotification extends Notification
{
    use Queueable;

    protected $userName;
    protected $url;
    protected $userId;

    public function __construct($userName, $url, $userId = null)
    {
        $this->userName = $userName;
        $this->url = $url;
        $this->userId = $userId;
    }

    /**
     * Catat percobaan akses terlarang atas nama pelakunya, lalu kabari semua superadmin.
     */
    public static function laporkan(string $keterangan, string $url): void
    {
        $pelaku = Auth::user();
        if (!$pelaku) {
            return;
        }

        ActivityLog::log(
            'Security Alert: Akses Ditolak',
            "Pengguna '{$pelaku->name}' (Role: {$pelaku->role}) {$keterangan}: {$url}",
            $pelaku->id
        );

        $superadmins = User::where('role', 'superadmin')->get();
        if ($superadmins->isNotEmpty()) {
            NotificationFacade::send($superadmins, new self($pelaku->name, $url, $pelaku->id));
        }
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Peringatan Keamanan: Akses Ditolak',
            'message' => 'Terdapat indikasi pelanggaran bypass akses dari pengguna ' . $this->userName,
            'type' => 'security',
            'notes' => $this->url,
            'user_id' => $this->userId,
        ];
    }
}
