<?php

namespace App\Services;

use App\Models\User;
use App\Models\Decision;

class Intercom extends BaseEvents
{
    private $intercom;

    public function __construct(\Nebo15\LumenIntercom\Intercom $intercom)
    {
        $this->intercom = $intercom;
    }

    public function decisionMake(Decision $decision, $userIds)
    {
        foreach ($userIds as $userId) {
            $this->intercom->getUser();
            $this->intercom->updateUser([
                'user_id' => $userId,
                'decisions_count' => 1,
                'last_decision_created_at' => time(),
            ], true);
        }
    }

    public function generateSecureCode($userId)
    {
        return hash_hmac('sha256', $userId, env('INTERCOM_APP_KEY'));
    }
}
