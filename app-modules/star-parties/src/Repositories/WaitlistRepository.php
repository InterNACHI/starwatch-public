<?php

namespace StarWatch\StarParties\Repositories;

use Illuminate\Support\Collection;
use StarWatch\StarParties\Enums\WaitlistEntryStatus;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\WaitlistEntry;

final class WaitlistRepository
{
    /**
     * Find the next waiting entry for a party, ordered by position.
     *
     * @param  StarParty  $party
     * @return WaitlistEntry|null
     */
    public function findNextWaiting(StarParty $party): ?WaitlistEntry
    {
        return WaitlistEntry::query()
            ->where('star_party_id', $party->id)
            ->where('status', WaitlistEntryStatus::Waiting)
            ->orderBy('position')
            ->first();
    }

    /**
     * Find an entry by party and user, regardless of status.
     *
     * @param  StarParty  $party
     * @param  int  $userId
     * @return WaitlistEntry|null
     */
    public function findByPartyAndUser(StarParty $party, int $userId): ?WaitlistEntry
    {
        return WaitlistEntry::query()
            ->where('star_party_id', $party->id)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * List all waiting entries for a party, ordered by position.
     *
     * @param  StarParty  $party
     * @return Collection<int, WaitlistEntry>
     */
    public function listWaitingForParty(StarParty $party): Collection
    {
        return WaitlistEntry::query()
            ->where('star_party_id', $party->id)
            ->where('status', WaitlistEntryStatus::Waiting)
            ->orderBy('position')
            ->get();
    }

    /**
     * Count the number of waiting entries for a party.
     *
     * @param  StarParty  $party
     * @return int
     */
    public function countWaiting(StarParty $party): int
    {
        return WaitlistEntry::query()
            ->where('star_party_id', $party->id)
            ->where('status', WaitlistEntryStatus::Waiting)
            ->count();
    }
}
