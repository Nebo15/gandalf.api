<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TokenNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('token_not_found');
    }
}
