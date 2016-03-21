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
        Validator::extend('conditionsField', 'App\Validators\TableValidator@conditionsField');
        Validator::extend('ruleThanType', 'App\Validators\TableValidator@ruleThanType');
        Validator::extend('ruleThanType', 'App\Validators\TableValidator@ruleThanType');
        Validator::extend('groupTablesFields', 'App\Validators\GroupValidator@tablesFields');
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
