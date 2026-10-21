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
        
        // Superadmin boleh masuk semua halaman admin/monitor, tetapi tidak ke halaman
        // yang murni milik user (pengajuan surat), sesuai .agent/workflows/superadmin_workflow.md
        if ($userRole === 'superadmin') {
            if (array_intersect($roles, ['admin', 'superadmin', 'monitor'])) {
                return $next($request);
            }

            return redirect()->route('dashboard')
                ->with('error', 'Halaman ini khusus untuk pengguna pengaju surat.');
        }

        // Jika role user ada dalam daftar roles yang diizinkan
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Catat atas nama pelaku dan kabari semua superadmin
        \App\Notifications\SecurityAlertNotification::laporkan('mencoba masuk ke rute terlarang secara paksa', $request->fullUrl());

        // Redirect dengan alert
        return redirect()->route('dashboard')
            ->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
} 