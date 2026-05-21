<?php

namespace StarWatch\StarParties\Events;

use Illuminate\Foundation\Events\Dispatchable;
use StarWatch\StarParties\Models\WaitlistEntry;

final class MemberJoinedWaitlist
{
    use Dispatchable;

    /**
     * Capture the waitlist entry that was just created.
     *
     * @param  WaitlistEntry  $entry
     * @return void
     */
    public function __construct(public readonly WaitlistEntry $entry)
    {
    }
}
