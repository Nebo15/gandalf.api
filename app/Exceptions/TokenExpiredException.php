<?php
namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class TokenExpiredException extends HttpException
{
    public function __construct()
    {
        parent::__construct(422, 'token_expired');
    }
}
