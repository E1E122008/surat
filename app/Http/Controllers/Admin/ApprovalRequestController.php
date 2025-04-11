<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalRequest;
use App\Models\User;
use App\Notifications\ApprovalRequestNotification;
use Illuminate\Http\Request;

class ApprovalRequestController extends Controller
{
    public function index()
    {
        $approvalRequests = ApprovalRequest::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        // Mark notifications as read
        auth()->user()->unreadNotifications
            ->where('type', 'App\Notifications\ApprovalRequestNotification')
            ->markAsRead();

        return view('admin.approval-requests.index', compact('approvalRequests'));
    }

    public function approve(Request $request, $id)
    {
        $approvalRequest = ApprovalRequest::findOrFail($id);
        $approvalRequest->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'notes' => $request->notes
        ]);

        // Send notification to user
        $approvalRequest->user->notify(new ApprovalRequestNotification($approvalRequest, 'approved'));

        return redirect()->back()->with('success', 'Permintaan berhasil disetujui');
    }

    public function reject(Request $request, $id)
    {
        $approvalRequest = ApprovalRequest::findOrFail($id);
        $approvalRequest->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now()
        ]);

        // Send notification to user
        $approvalRequest->user->notify(new ApprovalRequestNotification($approvalRequest, 'rejected'));

        return redirect()->back()->with('success', 'Permintaan berhasil ditolak');
    }
} 