<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\SendStatistic::class,
        Commands\DeleteExpiredTokens::class,
        Commands\DeleteExpiredProjectDumps::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('send:statistic')->everyMinute();
        $schedule->command('tokens:delete')->hourly();
        $schedule->command('dump:delete')->twiceDaily(1, 13);
    }
}
