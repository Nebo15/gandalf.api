<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('conditionType', 'App\Validators\DecisionStructValidator@conditionType');
        Validator::extend('conditionsCount', 'App\Validators\DecisionStructValidator@conditionsCount');
        Validator::extend('conditionsField', 'App\Validators\DecisionStructValidator@conditionsField');
    }

    public function register()
    {
    }
}
