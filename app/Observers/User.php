<?php
namespace App\Observers;

use App\Events\Profile;
use App\Models\UsedNicknames;

class User
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
    public function saving($user)
    {
        if ($user->isDirty('password')) {
            $user->password =  $user->getPasswordHasher()->make($user->password);
        }
    }
}
