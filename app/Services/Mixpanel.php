<?php

namespace App\Services;

use App\Models\Decision;

/**
 * Class Mixpanel
 * @package MBank\Service
 * @property \Nebo15\LumenMixpanel\Mixpanel $mixpanel
 *
 * $this->mixpanel->addTrackEvent('event_name', ["landing page" => "/specials"]);
 * $this->mixpanel->addUserEvent('set', '', ['$email'=>'email@example.com', '$first_name'=>'test user']);
 * $this->mixpanel->addUserEvent('increment', 'login_count', 1);
 * $this->mixpanel->addUserEvent('append', 'custom_property', ['custom_value1', 'custom_value2']);
 * $this->mixpanel->addUserEvent('trackCharge', '', 9.99);
 * $this->mixpanel->addUserEvent('trackCharge', '', [9.99, strtotime('NOW')]);
 */
class Mixpanel extends BaseEvents
{
    private $mixpanel;

    public function __construct(\Nebo15\LumenMixpanel\Mixpanel $mixpanel)
    {
        $this->mixpanel = $mixpanel;
    }

    public function decisionMake(Decision $decision, $userId)
    {
        $this->mixpanel->setIdentity($userId);
        $this->mixpanel->addUserEvent('increment', 'decisions_count', 1);
        $this->mixpanel->addTrackEvent('last_decision_created_at', ['created_at' => time()]);
    }
}
