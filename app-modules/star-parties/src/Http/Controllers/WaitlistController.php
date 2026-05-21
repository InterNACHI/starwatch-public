<?php

namespace StarWatch\StarParties\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\WaitlistEntry;

final class WaitlistController extends Controller
{
    /**
     * Add the current user to the party's waitlist.
     */
    public function store(Lodge $lodge, StarParty $party): RedirectResponse
    {
        $entry = DB::transaction(function() use ($party) {
            return $party->waitlistEntries()->firstOrCreate([
                'user_id' => Auth::id(),
            ]);
        });

        return to_route('star-parties::frontend.party.show', [$party->lodge, $party])
            ->with('status', "You're #{$entry->position()} on the waitlist.");
    }

    /**
     * Remove a user's waitlist entry.
     */
    public function destroy(Lodge $lodge, StarParty $party, WaitlistEntry $waitlist_entry): RedirectResponse
    {
        $waitlist_entry->delete();

        return to_route('star-parties::frontend.party.show', [$party->lodge, $party])
            ->with('status', 'You have left the waitlist.');
    }
}
