<?php

namespace App\Services;

use App\Models\User;
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

    public function userCreate(User $user)
    {
        $this->userCreateOrUpdate($user, 'user-create');
    }

    public function userUpdate(User $user)
    {
        $this->userCreateOrUpdate($user, 'user-update');
    }

    public function decisionMake(Decision $decision, array $user_ids)
    {
        if (false == env('MIXPANEL_ENABLED')) {
            return false;
        }
        foreach ($user_ids as $id) {
            $this->mixpanel->setIdentity($id);
            $this->mixpanel->addUserEvent('set', '', ['Last Decision created_at' => time()]);
            $this->mixpanel->addUserEvent('increment', 'Decisions count', 1);
        }
    }

    protected function userCreateOrUpdate(User $user, $type)
    {
        if (false == env('MIXPANEL_ENABLED')) {
            return false;
        }
        $this->mixpanel->setIdentity($user->getId());
        $this->mixpanel->addTrackEvent(
            $type,
            [
                'created_at' => time(),
                'username' => $user->username,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
            ]
        );
        $this->mixpanel->addUserEvent('set', $type, [
            '$email' => $user->email,
            '$username' => $user->username,
            '$first_name' => $user->first_name,
            '$last_name' => $user->last_name,
        ]);
    }
}
