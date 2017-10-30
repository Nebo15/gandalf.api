<?php

namespace App\Services;

use Illuminate\Hashing\BcryptHasher;

class Hasher
{
    public static function getToken($length = 32)
    {
        return str_replace(
            '/',
            '',
            (new BcryptHasher())->make(
                self::generateRandomString($length),
                ['salt' => random_bytes(22)]
            )
        );

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
