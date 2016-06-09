<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 10.12.15
 * Time: 21:10
 */

namespace App\Http\Middleware;

use Log;
use Closure;
use App\Services\DbTransfer;

class Terminable
{
    private $dbTransfer;

    public function __construct(DbTransfer $slack)
    {
        $this->dbTransfer = $slack;
    }

    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate()
    {
        $d = $this->dbTransfer->import();
        Log::info(print_r($d, true));
    }
}
