<?php
namespace App\Validators;

class PasswordValidator
{
    public function validate($attribute, $value)
    {
        return preg_match('/^\S*(?=\S*[A-Z])(?=\S*[a-z])(?=\S*[\d])\S*$/', $value);
    }
}
