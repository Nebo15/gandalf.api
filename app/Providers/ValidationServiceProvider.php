<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidationServiceProvider extends ServiceProvider
{

    public function boot()
    {
//        Validator::extend('conditionsStruct', 'App\Validators\ScoringStructValidator@conditionsTypes');
//        Validator::extend('rulesStruct', 'App\Validators\ScoringStructValidator@rules');
    }

    public function register()
    {
    }
}
