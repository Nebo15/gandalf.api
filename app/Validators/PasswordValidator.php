<?php
namespace App\Validators;

class PasswordValidator
{
    public function validate($attribute, $value)
    {
        return preg_match('/^\S*(?=\S{6,})(?=\S*[a-zA-Z])(?=\S*[\d])\S*$/', $value);
    }
}
