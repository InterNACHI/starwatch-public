<?php

namespace StarWatch\StarParties\Events;

use Illuminate\Foundation\Events\Dispatchable;
use StarWatch\StarParties\Models\WaitlistEntry;

final class MemberPromotedFromWaitlist
{
    use Dispatchable;

    /**
     * Capture the entry that was promoted to a confirmed RSVP.
     *
     * @param  WaitlistEntry  $entry
     * @return void
     */
    public function __construct(public readonly WaitlistEntry $entry)
    {
    }
}
