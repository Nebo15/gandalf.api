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
$api->api('groups', 'GroupsController', ['oauth', 'applicationable', 'applicationable.acl']);
$api->api('tables', 'TablesController', ['oauth', 'applicationable', 'applicationable.acl']);


/** @var Nebo15\Changelog\Router $changelog */
$changelog = $app->make('Nebo15\Changelog\Router');
$changelog->api('api/v1/admin', ['auth.admin']);

$app->make('oauth.routes')->makeRestRoutes();
$app->make('Applicationable.routes')->makeRoutes();

$app->post('api/v1/users/', [
    'uses' => 'App\Http\Controllers\UsersController@create',
    'middleware' => 'oauth.basic.client'
]);

/**
 * Get list of users
 */
$app->get('api/v1/users/', [
    'uses' => 'App\Http\Controllers\UsersController@readListWithFilters',
    'middleware' => 'oauth'
]);

$app->group(
    [
        'prefix' => 'api/v1/admin',
        'namespace' => 'App\Http\Controllers',
        'middleware' => ['oauth', 'applicationable', 'applicationable.acl']
    ],
    function ($app) use ($api) {
        /** @var Laravel\Lumen\Application $app */
        $app->get('/decisions', ['uses' => 'DecisionsController@readList']);
        $app->get('/decisions/{id}', ['uses' => 'DecisionsController@read']);
        $app->put('/decisions/{id}/meta', ['uses' => 'DecisionsController@updateMeta']);
        $app->get('/tables/{id}/analytics', ['uses' => 'TablesController@analytics']);
    }
);

$app->group(
    [
        'prefix' => 'api/v1',
        'namespace' => 'App\Http\Controllers',
        'middleware' => ['applicationable', 'applicationable.user_or_client', 'applicationable.acl'],
    ],
    function ($app) {
        /** @var Laravel\Lumen\Application $app */
        $app->get('/decisions/{id}', ['uses' => 'ConsumerController@decision']);
        $app->post('/tables/{id}/decisions', ['uses' => 'ConsumerController@tableCheck']);
        $app->post('/groups/{id}/decisions', ['uses' => 'ConsumerController@groupCheck']);
    }
);
