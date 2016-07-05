<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 08.11.15
 * Time: 12:15
 */

namespace App\Listeners;

use App\Events\Decisions;

use App\Services\Intercom;
use App\Services\Mixpanel;

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
        $this->intercom->decisionMake($event->decision);
        $this->mixpanel->decisionMake($event->decision);
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
    }
}
