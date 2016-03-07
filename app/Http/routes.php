<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return response('ok');
});


/** @var Nebo15\REST\Router $api */
$api = $app->make('Nebo15\REST\Router');
$api->api('api/v1/admin/groups', 'GroupsController', ['auth.admin']);
$api->api('api/v1/admin/tables', 'TablesController', ['auth.admin']);

$app->make('Oauth.routes')->makeRestRoutes();

$app->group(
    [
        'prefix' => 'api/v1/admin',
        'namespace' => 'App\Http\Controllers',
        'middleware' => ['auth.admin']
    ],
    function ($app) {
        /** @var Laravel\Lumen\Application $app */
        $app->get('/decisions', ['uses' => 'TablesController@history']);
        $app->get('/decisions/{id}', ['uses' => 'TablesController@historyItem']);
    }
);

$app->group(
    [
        'prefix' => 'api/v1',
        'namespace' => 'App\Http\Controllers',
        'middleware' => ['auth.consumer']
    ],
    function ($app) {
        /** @var Laravel\Lumen\Application $app */
        $app->get('/decisions/{id}', ['uses' => 'ConsumerController@decision']);
        $app->post('/tables/{id}/decisions', ['uses' => 'ConsumerController@tableCheck']);
        $app->post('/groups/{id}/decisions', ['uses' => 'ConsumerController@groupCheck']);
    }
);
