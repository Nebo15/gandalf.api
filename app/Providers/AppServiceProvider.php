<?php

namespace App\Providers;

use App\Models\Table;
use App\Services\DbTransfer;
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
        $this->app->singleton('\App\Services\DbTransfer', function () {
            return new DbTransfer(new Table);
        });
    }
}
