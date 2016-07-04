<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 01.07.16
 * Time: 15:39
 */

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteExpiredProjectDumps extends Command
{
    protected $signature = 'dump:delete';

    protected $description = 'Delete expired project dumps';

    public function handle()
    {
        $now = Carbon::now();
        $dir = __DIR__ . '/../../../public/dump/';
        foreach (scandir($dir) as $dump) {
            if (preg_match('/\d{4}-\d{2}-\d{2}_\d{2}:\d{2}:\d{2}/', $dump, $time) and
                $now->diffInHours(Carbon::createFromFormat('Y-m-d_H:i:s', $time[0])) >= 24
            ) {
                unlink($dir . $dump);
            }
        }
    }
}
