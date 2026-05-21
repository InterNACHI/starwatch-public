<?php

namespace StarWatch\StarParties\Policies;

use App\User;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\WaitlistEntry;

final class WaitlistPolicy
{
    /**
     * A user may join the waitlist when the party is in the future,
     * full, and they don't already have an RSVP or a live waitlist
     * entry.
     *
     * @param  User  $user
     * @param  StarParty  $party
     * @return bool
     */
    public function join(User $user, StarParty $party): bool
    {
        if (! $party->scheduled_at->isFuture()) {
            return false;
        }

        if (! $party->isFull()) {
            return false;
        }

        if ($party->hasRsvpFrom($user)) {
            return false;
        }

        $existing = $party->waitlistEntries()
            ->where('user_id', $user->id)
            ->where('status', 'waiting')
            ->exists();

        return ! $existing;
    }

    /**
     * A user may leave the waitlist if the entry is theirs and
     * still waiting.
     *
     * @param  User  $user
     * @param  WaitlistEntry  $entry
     * @return bool
     */
    public function leave(User $user, WaitlistEntry $entry): bool
    {
        return $entry->user_id === $user->id
            && $entry->status->value === 'waiting';
    }
}
