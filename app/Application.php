<?php
namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class Application extends \Laravel\Lumen\Application
{
    protected function getMonologHandler()
    {
        if (env('APP_ENV') == 'prod') {
            return (new StreamHandler(env('APP_LOG_PATH', 'php://stdout')))
                ->setFormatter(new LineFormatter(null, null, true, true));
        } else {
            parent::getMonologHandler();
        }
    }
}
