<?php

namespace App\Providers;

use App\Services\DbTransfer;
use Drunken\Manager as DrunkenManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $mongo = (new \MongoClient)->selectDB(env('DB_DATABASE'));
        $this->app->singleton('\App\Services\DbTransfer', function () use ($mongo) {
            return new DbTransfer(new DrunkenManager($mongo));
        });
    }
}
