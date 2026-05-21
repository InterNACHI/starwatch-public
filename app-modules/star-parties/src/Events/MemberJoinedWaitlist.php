<?php

namespace StarWatch\StarParties\Events;

use Illuminate\Foundation\Events\Dispatchable;
use StarWatch\StarParties\Models\WaitlistEntry;

final class MemberJoinedWaitlist
{
    use Dispatchable;

    /**
     * @param  WaitlistEntry  $entry
     */
    public function __construct(public readonly WaitlistEntry $entry)
    {
    }
}
