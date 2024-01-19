<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wedstrijd;
use App\Models\Registration;
use Illuminate\Auth\Access\Response;

class WedstrijdPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Wedstrijd $wedstrijd): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('dtc')) return true;
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Wedstrijd $wedstrijd): bool
    {
        if ($user->hasRole('dtc')) return true;
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Wedstrijd $wedstrijd): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Wedstrijd $wedstrijd): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Wedstrijd $wedstrijd): bool
    {
        return false;
    }

    public function import(User $user): bool
    {
        if ($user->hasRole('dtc')) return true;
        return false;
    }

    public function export(User $user): bool
    {
        if ($user->hasRole('dtc')) return true;
        return false;
    }

    public function process_scores(User $user, Wedstrijd $wedstrijd): bool
    {
        if ($user->hasRole('dtc')) return true;
        return false;
    }
}
