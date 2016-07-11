<?php
namespace App\Validators;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;

class UserValidator
{
    public function username($attribute, $value)
    {
        return preg_match('/^[a-zA-Z0-9-_\.]+$/', $value);
    }

    public function password($attribute, $value)
    {
        return preg_match('/^\S*(?=\S*[A-Z])(?=\S*[a-z])(?=\S*[\d])\S*$/', $value);
    }

    public function lastName($attribute, $value)
    {
        return preg_match('/^[a-zA-Z\']+$/', $value);
    }

    public function unique($attribute, $value, $parameters)
    {
        if (!$parameters) {
            throw new \InvalidArgumentException("Validation rule uniqueExceptUser requires 1 parameter");
        }
        $field = $parameters[0];
        /** @var User $user */
        if (!$user = \Auth::user()) {
            throw new AuthorizationException;
        }
        if ($user->$field == $value) {
            return true;
        }

        return 0 == User::where($field, $value)->get(['_id'])->count();
    }

    public function currentPassword($attribute, $value)
    {
        /** @var User $user */
        if (!$user = \Auth::user()) {
            throw new AuthorizationException;
        }

        return $user->getPasswordHasher()->check($value, $user->password);
    }
}
