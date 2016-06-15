<?php
namespace App\Validators;

class UsernameValidator
{
    public function validate($attribute, $value)
    {
        return preg_match('/^[a-zA-Z0-9-_\.]+$/', $value);
    }
}
