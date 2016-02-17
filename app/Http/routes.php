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
        'middleware' => ['auth.admin']
    ],
    function ($app) {
        /** @var Laravel\Lumen\Application $app */
        $app->get('/tables/decisions', ['uses' => 'DecisionsController@history']);
        $app->get('/tables/{id}/decisions', ['uses' => 'DecisionsController@historyItem']);

        $app->get('/tables', ['uses' => 'DecisionsController@index']);
        $app->post('/tables', ['uses' => 'DecisionsController@create']);
        $app->get('/tables/{id}', ['uses' => 'DecisionsController@get']);
        $app->put('/tables/{id}', ['uses' => 'DecisionsController@edit']);
        $app->delete('/tables/{id}', ['uses' => 'DecisionsController@delete']);
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
        $app->get('/tables/decisions', ['uses' => 'ConsumerController@decisions']);
        $app->get('/tables/{id}/decisions', ['uses' => 'ConsumerController@decision']);
        $app->post('/tables/{id}/check', ['uses' => 'ConsumerController@check']);
    }
);
