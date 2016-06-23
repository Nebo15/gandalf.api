<?php
namespace App\Validators;

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
}
