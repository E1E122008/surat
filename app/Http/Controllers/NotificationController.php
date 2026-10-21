<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->simplePaginate(15);
        return view('notifications.index', compact('notifications'));
    }

    public function read($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // Redirect kustom berdasarkan TIPE notifikasi dan ROLE
        if ($notification->type === 'App\Notifications\DataRequestNotification') {
            if (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin') {
                return redirect()->route('admin.approval-requests.index');
            }
            return redirect()->route('data-requests.show', $notification->data['approval_request_id']);
        }
        
        if ($notification->type === 'App\Notifications\ApprovalRequestNotification') {
            if (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin') {
                return redirect()->route('admin.approval-requests.index');
            }
            return redirect()->route('transaksi-surat.index');
        }

        if ($notification->type === 'App\Notifications\SecurityAlertNotification') {
            // Buka Riwayat Aktivitas, langsung difilter ke pengguna pelaku bila tercatat
            return redirect()->route('profile.activity', array_filter([
                'user_id' => $notification->data['user_id'] ?? null,
            ]));
        }

        if ($notification->type === 'App\Notifications\HelpRequestNotification') {
            if (auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin') {
                return redirect()->route('bantuan.index');
            }
            return redirect()->route('bantuan.riwayat');
        }
        
        return back();
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    }
}
