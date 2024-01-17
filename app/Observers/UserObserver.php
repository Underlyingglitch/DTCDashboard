<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function updating(User $user)
    {
        if ($user->active && $user->isDirty('active') && !$user->email_verified_at) {
            $user->update([
                'email_verified_at' => now(),
            ]);
        }
        if ($user->isDirty('email')) {
            $user->update([
                'email_verified_at' => null,
            ]);
        }
        if ($user->isDirty('last_seen_at')) {
            $user->timestamps = false;
        }
    }
}
