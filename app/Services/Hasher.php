<?php

namespace App\Services;

use Illuminate\Hashing\BcryptHasher;

class Hasher
{
    public static function getToken()
    {
        return (new BcryptHasher())->make(self::generateRandomString(32));
    }

    private static function generateRandomString($length)
    {
        $string = '';
        for ($i = 0; $i < $length; $i++) {
            $string .= chr(mt_rand(33, 126));
        }

        return $string;
    }
}
