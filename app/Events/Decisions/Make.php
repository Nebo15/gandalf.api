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
    public $admins;
    public $applications;

    public function __construct(Decision $decision, array $applications)
    {
        $this->decision = $decision;
        $this->applications = $applications;
    }
}
