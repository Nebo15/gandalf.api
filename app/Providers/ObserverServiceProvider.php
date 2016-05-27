<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 12.04.16
 * Time: 14:46
 */

namespace App\Providers;

use App\Models\Invitation;
use App\Models\Table;
use App\Models\User;
use App\Observers\InvitationsObserver;
use App\Observers\TableObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class ObserverServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any necessary services.
     *
     * @return void
     */
    public function boot()
    {
        Table::observe(new TableObserver);
        User::observe(new UserObserver);
        Invitation::observe(new InvitationsObserver);
    }

    public function register()
    {
    }
}
