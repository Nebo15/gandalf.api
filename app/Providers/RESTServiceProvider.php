<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 02.03.16
 * Time: 13:32
 */

namespace App\Providers;

use App\Services\RESTRouter;
use Illuminate\Support\ServiceProvider;

class RESTServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('REST.router', function ($app) {
            return new RESTRouter($app);
        });
    }
}
