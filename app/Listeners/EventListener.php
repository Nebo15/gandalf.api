<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 08.11.15
 * Time: 12:15
 */

namespace App\Listeners;

use App\Events\Users;
use App\Events\Decisions;

use App\Services\Intercom;
use App\Services\Mixpanel;
use Nebo15\LumenApplicationable\Models\Application;

class EventListener
{
    private $intercom;
    private $mixpanel;

    public function __construct(Intercom $intercom, Mixpanel $mixpanel)
    {
        $this->intercom = $intercom;
        $this->mixpanel = $mixpanel;
    }

    public function decisionMake(Decisions\Make $event)
    {
        $apps = Application::where('_id', $event->appId)
            ->where('users.role', 'admin')
            ->get(['users.user_id', 'users.role']);
        $userIds = [];
        foreach ($apps as $app) {
            foreach ($app->users as $user) {
                if ($user->role == 'admin') {
                    $userIds[] = strval($user->user_id);
                }
            }
        }
        $this->intercom->decisionMake($event->decision, $userIds);
        $this->mixpanel->decisionMake($event->decision, $userIds);
    }

    public function userCreate(Users\Create $event)
    {
        $this->mixpanel->userCreate($event->user);
        $this->intercom->userCreateOrUpdate($event->user);
    }

    public function userUpdate(Users\Update $event)
    {
        $this->mixpanel->userUpdate($event->user);
        $this->intercom->userCreateOrUpdate($event->user);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher $events
     * @return array
     */
    public function subscribe($events)
    {
        $events->listen('App\Events\Decisions\Make', 'App\Listeners\EventListener@decisionMake');
        $events->listen('App\Events\Users\Create', 'App\Listeners\EventListener@userCreate');
        $events->listen('App\Events\Users\Update', 'App\Listeners\EventListener@userUpdate');
    }
}
