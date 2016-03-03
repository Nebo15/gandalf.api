<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 02.03.16
 * Time: 13:34
 */

namespace App\Services;

use Laravel\Lumen\Application;

class RESTRouter
{
    private $app;
    
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function api($route, $controllerName)
    {
        # ToDo: validate controller instance
//        if (!($controllerName instanceof App\Http\Controllers\RESTController)) {
//            throw new \Exception(
//                "Controller $controllerName should be instance of App\\Http\\Controllers\\RESTController"
//            );
//        }

        $this->app->get("/$route", ["uses" => "$controllerName@readList"]);
        $this->app->post("/$route", ["uses" => "$controllerName@create"]);
        $this->app->get("/$route/{id}", ["uses" => "$controllerName@read"]);
        $this->app->put("/$route/{id}", ["uses" => "$controllerName@update"]);
        $this->app->post("/$route/{id}/clone", ["uses" => "$controllerName@copy"]);
        $this->app->delete("/$route/{id}", ["uses" => "$controllerName@delete"]);
    }
}
