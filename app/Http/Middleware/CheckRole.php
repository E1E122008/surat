<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userRole = Auth::user()->role;
        
        // Superadmin bypasses all role restrictions (Hak akses penuh/God mode)
        if ($userRole === 'superadmin') {
            return $next($request);
        }

        // Jika role user ada dalam daftar roles yang diizinkan
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Temukan superadmin untuk menyimpan log aktivitas kepadanya
        $superadmin = \App\Models\User::where('role', 'superadmin')->first();
        if ($superadmin) {
            \App\Models\ActivityLog::log(
                'Security Alert: Akses Ditolak',
                "Pengguna '" . Auth::user()->name . "' (Role: " . Auth::user()->role . ") mencoba masuk ke rute terlarang secara paksa: " . $request->fullUrl(),
                $superadmin->id
            );
            $superadmin->notify(new \App\Notifications\SecurityAlertNotification(Auth::user()->name, $request->fullUrl()));
        }

        // Redirect dengan alert
        return redirect()->route('dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
} 