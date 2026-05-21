<?php

namespace StarWatch\StarParties\Services;

use App\User;
use Illuminate\Support\Facades\DB;
use StarWatch\StarParties\Enums\RsvpStatus;
use StarWatch\StarParties\Enums\WaitlistEntryStatus;
use StarWatch\StarParties\Events\MemberJoinedWaitlist;
use StarWatch\StarParties\Events\MemberPromotedFromWaitlist;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;
use StarWatch\StarParties\Models\WaitlistEntry;
use StarWatch\StarParties\Repositories\WaitlistRepository;

final class WaitlistService
{
    public function __construct(
        private readonly WaitlistRepository $repository,
    ) {
    }

    /**
     * Add a user to a party's waitlist. If the user already has an
     * entry, return it (idempotent on retries).
     *
     * @param  StarParty  $party
     * @param  User  $user
     * @return WaitlistEntry
     */
    public function addToWaitlist(StarParty $party, User $user): WaitlistEntry
    {
        $existing = $this->repository->findByPartyAndUser($party, $user->getKey());

        if ($existing !== null) {
            return $existing;
        }

        $nextPosition = $this->repository->countWaiting($party) + 1;

        $entry = new WaitlistEntry();
        $entry->star_party_id = $party->getKey();
        $entry->user_id = $user->getKey();
        $entry->position = $nextPosition;
        $entry->status = WaitlistEntryStatus::Waiting;
        $entry->joined_at = now();
        $entry->save();

        MemberJoinedWaitlist::dispatch($entry);

        return $entry;
    }

    /**
     * Remove a user's waitlist entry (mark as cancelled) and
     * resequence the remaining waiting positions.
     *
     * @param  WaitlistEntry  $entry
     * @return void
     */
    public function removeFromWaitlist(WaitlistEntry $entry): void
    {
        $party = $entry->starParty;

        $entry->status = WaitlistEntryStatus::Cancelled;
        $entry->save();

        $this->resequencePositions($party);
    }

    /**
     * Promote the next waiting member into a confirmed RSVP.
     * Returns the newly created RSVP, or null if there's no one
     * waiting.
     *
     * @param  StarParty  $party
     * @return StarPartyRsvp|null
     */
    public function promoteNext(StarParty $party): ?StarPartyRsvp
    {
        return DB::transaction(function() use ($party) {
            $next = $this->repository->findNextWaiting($party);

            if ($next === null) {
                return null;
            }

            $rsvp = $party->rsvps()->firstOrCreate(
                ['user_id' => $next->user_id],
                ['status' => RsvpStatus::Confirmed],
            );

            $next->status = WaitlistEntryStatus::Promoted;
            $next->save();

            MemberPromotedFromWaitlist::dispatch($next);

            $this->resequencePositions($party);

            return $rsvp;
        });
    }

    /**
     * Return the 1-based position of a waiting entry within its
     * party's queue. Falls back to the entry's stored position when
     * it's not currently waiting.
     *
     * @param  WaitlistEntry  $entry
     * @return int
     */
    public function getPosition(WaitlistEntry $entry): int
    {
        return (int) $entry->position;
    }

    /**
     * Renumber every Waiting entry for the party so positions are
     * dense and 1-based, ordered by `joined_at` then `id`.
     *
     * @param  StarParty  $party
     * @return void
     */
    public function resequencePositions(StarParty $party): void
    {
        $waiting = WaitlistEntry::query()
            ->where('star_party_id', $party->getKey())
            ->where('status', WaitlistEntryStatus::Waiting)
            ->orderBy('joined_at')
            ->orderBy('id')
            ->get();

        $position = 1;

        foreach ($waiting as $entry) {
            if ($entry->position !== $position) {
                $entry->position = $position;
                $entry->save();
            }

            $position++;
        }
    }
}
