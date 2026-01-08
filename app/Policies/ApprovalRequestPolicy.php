<?php

namespace App\Policies;

use App\Models\ApprovalRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ApprovalRequestPolicy
{
    use HandlesAuthorization;

    public function view(User $user, ApprovalRequest $approvalRequest)
    {
        // Admin dan monitor bisa melihat semua data
        if ($user->role == 'admin' || $user->role == 'monitor') {
            return true;
        }
        // User biasa hanya bisa melihat data miliknya sendiri
        return $user->id == $approvalRequest->user_id;
    }

    public function update(User $user, ApprovalRequest $approvalRequest)
    {
        // Hanya admin yang bisa approve/reject
        if ($user->role == 'admin') {
            return $approvalRequest->status == 'pending';
        }
        // User biasa hanya bisa update data miliknya sendiri yang sudah approved
        return $user->id == $approvalRequest->user_id && $approvalRequest->status == 'approved';
    }
}
