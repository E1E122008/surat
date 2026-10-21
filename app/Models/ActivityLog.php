<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'nama_pengguna',
        'action',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Nama pelaku untuk ditampilkan. Bila akunnya sudah dihapus, riwayat tetap ada
     * dan ditandai "(Akun dihapus)".
     */
    public function getNamaPelakuAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }

        return $this->nama_pengguna ? $this->nama_pengguna . ' (Akun dihapus)' : 'System';
    }

    /**
     * Static helper to log an activity gracefully without crashing main execution.
     */
    public static function log($action, $description = null, $userId = null)
    {
        try {
            $userId = $userId ?? auth()->id();

            self::create([
                'user_id' => $userId,
                // Salinan nama agar riwayat tetap terbaca setelah akunnya dihapus
                'nama_pengguna' => $userId ? User::whereKey($userId)->value('name') : null,
                'action' => $action,
                'description' => $description,
            ]);
        } catch (\Exception $e) {
            // Fails silently to prevent interrupting main app flow
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }
}
