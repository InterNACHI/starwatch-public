<?php

namespace StarWatch\StarParties\Policies;

use App\User;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\WaitlistEntry;

class WaitlistEntryPolicy
{
    /**
     * Determine whether the user may join the given star party's
     * waitlist. A user may join when the event is still upcoming,
     * the event is at capacity, the user is not already confirmed
     * for the event, and the user is not already on the waitlist.
     *
     * @param  User  $user
     * @param  StarParty  $party
     * @return bool
     */
    public function create(User $user, StarParty $party): bool
    {
        return $party->scheduled_at->isFuture()
            && $party->isFull()
            && ! $party->hasRsvpFrom($user)
            && ! $party->waitlistEntries()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user may remove the given waitlist entry.
     * A user may only remove their own entry.
     *
     * @param  User  $user
     * @param  WaitlistEntry  $entry
     * @return bool
     */
    public function delete(User $user, WaitlistEntry $entry): bool
    {
        return $entry->user_id === $user->id;
    }
}
