<?php

namespace StarWatch\StarParties\Policies;

use App\User;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\WaitlistEntry;

class WaitlistEntryPolicy
{
    public function create(User $user, StarParty $party): bool
    {
        return $party->scheduled_at->isFuture()
            && $party->isFull()
            && ! $party->hasRsvpFrom($user)
            && ! $party->waitlistEntries()->where('user_id', $user->getKey())->exists();
    }

    public function delete(User $user, WaitlistEntry $entry): bool
    {
        return $entry->user_id === $user->getKey();
    }
}
