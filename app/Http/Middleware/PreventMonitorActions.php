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
            abort(403, 'Anda tidak memiliki izin untuk melakukan aksi ini. Role monitor hanya dapat melihat data.');
        }

        return $next($request);
    }
}
