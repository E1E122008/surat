<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Static helper to log an activity gracefully without crashing main execution.
     */
    public static function log($action, $description = null, $userId = null)
    {
        try {
            self::create([
                'user_id' => $userId ?? auth()->id(),
                'action' => $action,
                'description' => $description,
            ]);
        } catch (\Exception $e) {
            // Fails silently to prevent interrupting main app flow
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }
}
