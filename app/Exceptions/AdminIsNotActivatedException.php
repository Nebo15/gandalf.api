<?php
namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class AdminIsNotActivatedException extends HttpException
{
    public function __construct()
    {
        parent::__construct(403, 'Project owner is not activated, try again later');
    }
}
