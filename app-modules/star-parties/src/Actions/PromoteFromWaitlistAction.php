<?php

namespace StarWatch\StarParties\Actions;

use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;
use StarWatch\StarParties\Services\WaitlistService;

final class PromoteFromWaitlistAction
{
    public function __construct(
        private readonly WaitlistService $service,
    ) {
    }

    /**
     * Promote the next waitlist entry on the given party. Returns
     * the newly created RSVP, or null if the waitlist is empty.
     *
     * @param  StarParty  $party
     * @return StarPartyRsvp|null
     */
    public function execute(StarParty $party): ?StarPartyRsvp
    {
        return $this->service->promoteNext($party);
    }
}
