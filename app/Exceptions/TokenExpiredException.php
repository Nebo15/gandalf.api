<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 03.12.15
 * Time: 10:08
 */

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class TokenExpiredException extends HttpException
{
    public function __construct()
    {
        parent::__construct(422, 'token_expired');
    }
}
