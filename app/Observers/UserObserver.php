<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Observers;

class UserObserver
{
    /**
     * @param \App\Models\User $user
     */
    public function creating($user)
    {
        if (!$user->nickname) {
            if ($user->temporary_email) {
                list($user->nickname) = explode('@', $user->temporary_email);
            }
        }
    }
    /**
     * @param \App\Models\User $user
     */
    public function created($user)
    {}
    /**
     * @param \App\Models\User $user
     */
    public function updating($user)
    {}
    /**
     * @param \App\Models\User $user
     */
    public function updated($user)
    {}
    /**
     * @param \App\Models\User $user
     */
    public function saving($user)
    {
        if ($user->isDirty('password')) {
            $user->password =  $user->getPasswordHasher()->make($user->password);
        }
    }
    /**
     * @param \App\Models\User $user
     */
    public function saved($user)
    {}
    /**
     * @param \App\Models\User $user
     */
    public function deleting($user)
    {}
    /**
     * @param \App\Models\User $user
     */
    public function deleted($user)
    {}
    /**
     * @param \App\Models\User $user
     */
    public function restoring($user)
    {}
    /**
     * @param \App\Models\User $user
     */
    public function restored($user)
    {}
}
