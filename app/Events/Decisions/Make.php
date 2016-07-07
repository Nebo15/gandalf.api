<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 05.07.16
 * Time: 14:48
 */

namespace App\Events\Decisions;

use App\Models\Decision;

class Make
{
    public $appId;
    public $decision;

    public function __construct(Decision $decision, $app_id)
    {
        $this->decision = $decision;
        $this->appId = $app_id;
    }
}
