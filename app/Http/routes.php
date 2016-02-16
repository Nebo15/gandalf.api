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
        'prefix' => 'api/v1',
        'namespace' => 'App\Http\Controllers',
    //        'middleware' => ['auth']
    ],
    function ($app) {
        /** @var Laravel\Lumen\Application $app */
        $app->get('/decisions', ['uses' => 'DecisionsController@index']);
        $app->put('/decisions', ['uses' => 'DecisionsController@set']);

        $app->get('/scoring', ['uses' => 'ScoringController@history']);
        $app->get('/scoring/{id}', ['uses' => 'ScoringController@item']);
        $app->post('/scoring/check', ['uses' => 'ScoringController@check']);
    }
);
