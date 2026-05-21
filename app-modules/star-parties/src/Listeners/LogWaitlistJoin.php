<?php

namespace StarWatch\StarParties\Listeners;

use Illuminate\Support\Facades\Log;
use StarWatch\StarParties\Events\MemberJoinedWaitlist;

final class LogWaitlistJoin
{
    /**
     * Log when a member is added to a party's waitlist.
     *
     * @param  MemberJoinedWaitlist  $event
     * @return void
     */
    public function handle(MemberJoinedWaitlist $event): void
    {
        $entry = $event->entry;

        Log::info('Member joined waitlist', [
            'star_party_id' => $entry->star_party_id,
            'user_id' => $entry->user_id,
            'position' => $entry->position,
        ]);
    }
}
