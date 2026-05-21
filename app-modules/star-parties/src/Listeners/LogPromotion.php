<?php

namespace StarWatch\StarParties\Listeners;

use Illuminate\Support\Facades\Log;
use StarWatch\StarParties\Events\MemberPromotedFromWaitlist;

final class LogPromotion
{
    /**
     * Log when a member is promoted from a party's waitlist.
     *
     * @param  MemberPromotedFromWaitlist  $event
     * @return void
     */
    public function handle(MemberPromotedFromWaitlist $event): void
    {
        $entry = $event->entry;

        Log::info('Member promoted from waitlist', [
            'star_party_id' => $entry->star_party_id,
            'user_id' => $entry->user_id,
            'waitlist_entry_id' => $entry->getKey(),
        ]);
    }
}
