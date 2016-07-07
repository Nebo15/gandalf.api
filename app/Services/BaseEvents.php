<?php

namespace App\Services;

use App\Models\User;
use App\Models\Decision;

abstract class BaseEvents
{
    abstract public function decisionMake(Decision $decision, array $user_ids);
}
