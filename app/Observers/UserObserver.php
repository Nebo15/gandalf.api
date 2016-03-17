<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function creating(User $user)
    {
        if (!$user->nickname) {
            if ($user->temporary_email) {
                list($user->nickname) = explode('@', $user->temporary_email);
            }
        }
    }

    public function created(User $user)
    {
    }

    public function updating(User $user)
    {
    }

    public function updated(User $user)
    {
    }

    public function saving(User $user)
    {
        if ($user->isDirty('password')) {
            $user->password = $user->getPasswordHasher()->make($user->password);
        }
    }

    public function saved(User $user)
    {
    }

    public function deleting(User $user)
    {
    }

    public function deleted(User $user)
    {
    }

    public function restoring(User $user)
    {
    }

    public function restored(User $user)
    {
    }
}
