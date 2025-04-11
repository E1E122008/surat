<?php

namespace App\Policies;

use App\Models\DataRequest;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DataRequestPolicy
{
    use HandlesAuthorization;

    public function view(User $user, DataRequest $dataRequest)
    {
        return $user->role == 'admin' || $user->id == $dataRequest->user_id;
    }

    public function update(User $user, DataRequest $dataRequest)
    {
        if ($user->role == 'admin') {
            return $dataRequest->status == 'pending';
        }
        return $user->id == $dataRequest->user_id && $dataRequest->status == 'approved';
    }
} 