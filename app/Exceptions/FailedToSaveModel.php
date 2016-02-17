<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 17.02.16
 * Time: 16:01
 */

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class FailedToSaveModel extends HttpException
{
    public function __construct()
    {
        parent::__construct(400, 'failed_to_save_model');
    }
}
