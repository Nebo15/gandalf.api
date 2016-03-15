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

$app->make('Oauth.routes')->makeRestRoutes();
$app->make('Applicationable.routes')->makeRoutes();


$app->group(['namespace' => 'App\Http\Controllers'],
    function ($app) use ($api) {
        $app->post('api/v1/user/', ['uses' => 'UsersController@create', 'middleware' => 'oauth.basic.client']);
    }
);


$app->group(
    [
        'prefix' => 'api/v1/admin',
        'namespace' => 'App\Http\Controllers',
    ],
    function ($app) use ($api) {
        $api->api('/groups', 'GroupsController', ['auth.admin']);
        $api->api('/tables', 'TablesController', ['oauth', 'applicationable']);

        /** @var Laravel\Lumen\Application $app */
        $app->get('/decisions', ['uses' => 'TablesController@history']);
        $app->get('/decisions/{id}', ['uses' => 'TablesController@historyItem']);
    }
);

$app->group(
    [
        'prefix' => 'api/v1',
        'namespace' => 'App\Http\Controllers',
        'middleware' => ['oauth'],
    ],
    function ($app) {
        /** @var Laravel\Lumen\Application $app */
        $app->get('/decisions/{id}', ['uses' => 'ConsumerController@decision']);
        $app->post('/tables/{id}/decisions', ['uses' => 'ConsumerController@tableCheck']);
        $app->post('/groups/{id}/decisions', ['uses' => 'ConsumerController@groupCheck']);
    }
);
