<?php

namespace App\Services;

use Illuminate\Hashing\BcryptHasher;

class Hasher
{
    public static function getToken()
    {
        return (new BcryptHasher())->make(self::generateRandomString(32));
    }

    public static function getTokenPhone($length = 5)
    {
        $random = [];
        for ($i = 0; $i < $length; $i++) {
            $random[] = rand(1, 9);
        }
        shuffle($random);
        $token = implode(null, $random);

        $verhoeff = new Verhoeff;

        return $verhoeff->generateCheckDigit($token) . $token;
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
