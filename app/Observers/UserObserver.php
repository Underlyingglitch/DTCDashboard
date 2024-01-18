<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    public function updating(User $user)
    {
        if ($user->active && $user->isDirty('active') && !$user->email_verified_at) {
            $user->update([
                'email_verified_at' => now(),
            ]);
            $user->notify(new \App\Notifications\AccountActivated($user));
        }
        if ($user->isDirty('last_seen_at')) {
            $user->timestamps = false;
        }
    }
}
