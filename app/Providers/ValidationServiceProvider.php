<?php
namespace App\Providers;

use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('conditionType', 'App\Validators\TableValidator@conditionType');
        Validator::extend('conditionsCount', 'App\Validators\TableValidator@conditionsCount');
        Validator::extend('conditionsFieldKey', 'App\Validators\TableValidator@conditionsFieldKey');
        Validator::extend('ruleThanType', 'App\Validators\TableValidator@ruleThanType');
        Validator::extend('probabilitySum', 'App\Validators\TableValidator@probabilitySum');
        Validator::extend('decision_type', 'App\Validators\TableValidator@decisionType');
        Validator::extend('password', 'App\Validators\UserValidator@password');
        Validator::extend('username', 'App\Validators\UserValidator@username');
        Validator::extend('last_name', 'App\Validators\UserValidator@lastName');
        Validator::extend('mongoId', 'App\Validators\GeneralValidator@mongoId');
        Validator::extend('betweenString', 'App\Validators\GeneralValidator@betweenString');
        Validator::extend('json', 'App\Validators\GeneralValidator@json');
    }

    public function register()
    {
        $this->app->singleton('validation.presence', function ($app) {
            return new DatabasePresenceVerifier($app['db']);
        });

        $this->app->singleton('validator', function ($app) {
            $validator = new Factory($app['translator'], $app);
            $validator->resolver(function ($translator, $data, $rules, $messages, $customAttributes) {
                return new \App\Http\Services\Validator($translator, $data, $rules, $messages, $customAttributes);
            });

            // The validation presence verifier is responsible for determining the existence
            // of values in a given data collection, typically a relational database or
            // other persistent data stores. And it is used to check for uniqueness.
            if (isset($app['validation.presence'])) {
                $validator->setPresenceVerifier($app['validation.presence']);
            }

            return $validator;
        });
    }
}
