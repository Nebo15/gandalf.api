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

$app->group(
    [
        'prefix' => 'api/v1/admin',
        'namespace' => 'App\Http\Controllers',
//        'middleware' => ['auth.user']
    ],
    function ($app) {
        /** @var Laravel\Lumen\Application $app */
        $app->get('/tables', ['uses' => 'DecisionsController@index']);
        $app->post('/tables', ['uses' => 'DecisionsController@create']);
        $app->get('/tables/{id}', ['uses' => 'DecisionsController@get']);
        $app->put('/tables/{id}', ['uses' => 'DecisionsController@edit']);
        $app->delete('/tables/{id}', ['uses' => 'DecisionsController@delete']);

        $app->get('/decisions', ['uses' => 'DecisionsController@results']);
        $app->get('/decisions/{id}', ['uses' => 'DecisionsController@result']);
    }
);

$app->group(
    [
        'prefix' => 'api/v1',
        'namespace' => 'App\Http\Controllers',
//        'middleware' => ['auth.user']
    ],
    function ($app) {
        /** @var Laravel\Lumen\Application $app */
        $app->get('/decisions', ['uses' => 'ConsumerController@decisions']);
        $app->get('/decisions/{id}', ['uses' => 'ConsumerController@decision']);
        $app->post('/decisions/check', ['uses' => 'ConsumerController@check']);
    }
);
