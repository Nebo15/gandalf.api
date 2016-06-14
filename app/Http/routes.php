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
$api->api('tables', 'TablesController', ['oauth', 'applicationable', 'applicationable.acl']);


/** @var Nebo15\Changelog\Router $changelog */
$changelog = $app->make('Nebo15\Changelog\Router');
$changelog->api(
    'api/v1/admin',
    ['oauth', 'applicationable', 'applicationable.acl'],
    'App\Http\Controllers\ChangelogController'
);


$app->make('oauth.routes')->makeRestRoutes();
$app->make('Applicationable.routes')->makeRoutes();

$app->group(
    [
        'prefix' => 'api/v1',
        'namespace' => 'App\Http\Controllers',
        'middleware' => ['oauth.basic.client'],
    ],
    function ($app) {
        /** @var Laravel\Lumen\Application $app */
        $app->post('/users', ['uses' => 'UsersController@create']);
        $app->post('/users/verify/email', ['uses' => 'UsersController@verifyEmail']);
        $app->post('/users/password/reset', ['uses' => 'UsersController@createResetPasswordToken']);
        $app->put('/users/password/reset', ['uses' => 'UsersController@changePassword']);
    }
);

$app->group(
    [
        'prefix' => 'api/v1',
        'namespace' => 'App\Http\Controllers',
        'middleware' => ['oauth', 'applicationable'],
    ],
    function ($app) {
        /** @var Laravel\Lumen\Application $app */
        $app->get('/users/current', ['uses' => 'UsersController@getUserInfo']);
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
        $app->put('/users/current', ['uses' => 'UsersController@updateUser']);
        #Get list of users
        $app->get('/users', ['uses' => 'UsersController@readListWithFilters']);

    }
);

$app->delete('api/v1/projects', [
    'uses' => 'App\Http\Controllers\ProjectsController@deleteProject',
    'middleware' => ['oauth', 'applicationable', 'applicationable.acl'],
]);
$app->get('api/v1/projects/export', [
    'uses' => 'App\Http\Controllers\ProjectsController@export',
    'middleware' => ['oauth', 'applicationable', 'applicationable.acl'],
]);

$app->group(
    [
        'prefix' => 'api/v1/admin',
        'namespace' => 'App\Http\Controllers',
        'middleware' => ['oauth', 'applicationable', 'applicationable.acl'],
    ],
    function ($app) {
        /** @var Laravel\Lumen\Application $app */

        $app->get('/decisions', ['uses' => 'DecisionsController@readList']);
        $app->get('/decisions/{id}', ['uses' => 'DecisionsController@read']);
        $app->put('/decisions/{id}/meta', ['uses' => 'DecisionsController@updateMeta']);
        $app->get('/tables/{id}/{variant_id:[0-9a-z]{24}}/analytics', ['uses' => 'TablesController@analytics']);
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
        $app->post('/invite', ['uses' => 'UsersController@invite']);
    }
);
