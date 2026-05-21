<?php

namespace StarWatch\StarParties\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use StarWatch\StarParties\Events\MemberJoinedWaitlist;
use StarWatch\StarParties\Events\MemberPromotedFromWaitlist;
use StarWatch\StarParties\Listeners\LogPromotion;
use StarWatch\StarParties\Listeners\LogWaitlistJoin;
use StarWatch\StarParties\Models\StarPartyRsvp;
use StarWatch\StarParties\Observers\StarPartyRsvpObserver;

class StarPartiesServiceProvider extends ServiceProvider
{
    /**
     * @var array<string, array<int, string>>
     */
    protected array $listen = [
        MemberJoinedWaitlist::class => [
            LogWaitlistJoin::class,
        ],
        MemberPromotedFromWaitlist::class => [
            LogPromotion::class,
        ],
    ];

    /**
     * Register container bindings. Nothing custom is needed here yet.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Wire up the RSVP observer and the explicit event/listener map.
     *
     * @return void
     */
    public function boot(): void
    {
        StarPartyRsvp::observe(StarPartyRsvpObserver::class);

        foreach ($this->listen as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
