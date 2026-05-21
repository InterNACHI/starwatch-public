<?php

namespace StarWatch\StarParties\Observers;

use StarWatch\StarParties\Actions\PromoteFromWaitlistAction;
use StarWatch\StarParties\Models\StarPartyRsvp;

final class StarPartyRsvpObserver
{
    /**
     * @param  PromoteFromWaitlistAction  $promote
     * @return void
     */
    public function __construct(
        private readonly PromoteFromWaitlistAction $promote,
    ) {
    }

    /**
     * When a confirmed RSVP is deleted, attempt to promote the next
     * waiting member.
     *
     * @param  StarPartyRsvp  $rsvp
     * @return void
     */
    public function deleted(StarPartyRsvp $rsvp): void
    {
        $party = $rsvp->star_party;

        if ($party === null) {
            return;
        }

        $this->promote->execute($party);
    }
}
