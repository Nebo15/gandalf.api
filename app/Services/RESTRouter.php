<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 02.03.16
 * Time: 13:34
 */

namespace App\Services;


class RESTRouter
{
    private $app;
    
    public function __construct(\App $app)
    {
        $this->app = $app;
    }

    public function api($route, $controller)
    {
        $this->app->get("/$route", ["uses" => "$controller@index"]);
        $this->app->post("/$route", ["uses" => "$controller@create"]);
        $this->app->get("/$route/{id}", ["uses" => "$controller@get"]);
        $this->app->put("/$route/{id}", ["uses" => "$controller@update"]);
        $this->app->post("/$route/{id}/clone", ["uses" => "$controller@cloneModel"]);
        $this->app->delete("/$route/{id}", ["uses" => "$controller@delete"]);
    }
}