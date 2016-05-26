<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 26.05.16
 * Time: 12:49
 */

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VariantNotFound extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('variant_not_found');
    }
}
