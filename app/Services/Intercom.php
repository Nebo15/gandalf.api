<?php

namespace App\Services;

use App\Models\Decision;

class Intercom extends BaseEvents
{
    private $intercom;

    public function __construct(\Nebo15\LumenIntercom\Intercom $intercom)
    {
        $this->intercom = $intercom;
    }

    public function decisionMake(Decision $decision)
    {

    }
}
