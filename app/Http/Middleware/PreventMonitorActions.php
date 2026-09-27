<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventMonitorActions
{
    /**
     * Handle an incoming request.
     * Mencegah role monitor melakukan aksi (create, edit, destroy, export)
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'monitor') {
            // Log the unauthorized attempt to superadmin
            $superadmin = \App\Models\User::where('role', 'superadmin')->first();
            if ($superadmin) {
                \App\Models\ActivityLog::log(
                    'Security Alert: Akses Ditolak',
                    "Pengguna '" . Auth::user()->name . "' (Role: " . Auth::user()->role . ") mencoba mengeksekusi aksi terlarang: " . $request->fullUrl(),
                    $superadmin->id
                );
                $superadmin->notify(new \App\Notifications\SecurityAlertNotification(Auth::user()->name, $request->fullUrl()));
            }

            return redirect()->route('dashboard')
                ->with('error', 'Anda tidak memiliki izin untuk melakukan aksi ini. Role monitor hanya dapat melihat data.');
        }

        return $next($request);
    }
}
