<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // 1. GLOBAL FILTER: Sembunyikan seluruh rekaman aktivitas dari pengguna "superadmin" untuk BUKAN superadmin
        // Namun, intership ini sekarang superadmin, dan superadmin bisa melihat SATU SAMA LAIN.
        // Berdasarkan instruksi: superadmin dapat melihat seluruh aktivitas user.
        // User lain (admin, user, monitor) hanya dapat melihat aktivitasnya sendiri.

        if (Auth::user()->role !== 'superadmin') {
            // Bukan superadmin hanya bisa melihat log miliknya, tanpa log login/logout (jika memang dulu disembunyikan)
            $query->where('user_id', Auth::id())
                ->whereNotIn('action', ['login', 'logout']);
        } else {
            // **SUPERADMIN**
            // Superadmin dapat melihat SEMUA. Termasuk melihat login/logout admin maupun staf.
            if ($request->has('user_id') && $request->user_id !== '') {
                $query->where('user_id', $request->user_id);
            }
        }
        $logs = $query->paginate(20)->withQueryString();

        // Get filter options ONLY for superadmin
        $allUsers = Auth::user()->role === 'superadmin'
            ? \App\Models\User::all()
            : collect([]);

        return view('activity-logs.index', compact('logs', 'allUsers'));
    }
}
