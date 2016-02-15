<?php
namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class IdNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('mongo_id_not_found');
    }
}
