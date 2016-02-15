<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class ValidationServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Validator::extend('decisionStruct', 'App\Validators\DecisionStructValidator@decision');
    }

    public function register()
    {
    }
}
