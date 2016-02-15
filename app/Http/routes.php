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
    return $app->version();
});

$app->group(
    [
        'prefix' => 'api/v1',
        'namespace' => 'App\Http\Controllers',
//        'middleware' => ['auth']
    ],
    function ($app) {
        /** @var Laravel\Lumen\Application $app */
        $app->get('/decisions', ['uses' => 'DecisionsController@index']);
        $app->post('/decisions', ['uses' => 'DecisionsController@set']);

        $app->get('/scoring/history', ['uses' => 'ScoringController@history']);
        $app->post('/scoring/check', ['uses' => 'ScoringController@check']);
    }
);