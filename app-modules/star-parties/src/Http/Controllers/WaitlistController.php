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
     * Add the currently authenticated user to the given star party's
     * waitlist. The lodge parameter is consumed by Laravel's scoped
     * route binding and is not used directly in this action.
     *
     * @param  Lodge  $lodge
     * @param  StarParty  $party
     * @return RedirectResponse
     */
    public function store(Lodge $lodge, StarParty $party): RedirectResponse
    {
        $this->authorize('create', [WaitlistEntry::class, $party]);

        $entry = DB::transaction(function() use ($party) {
            return $party->waitlistEntries()->firstOrCreate([
                'user_id' => Auth::id(),
            ]);
        });

        return redirect()
            ->route('star-parties::frontend.party.show', [$party->lodge, $party])
            ->with('status', "You're #{$entry->position()} on the waitlist.");
    }

    /**
     * Remove a member's waitlist entry. The lodge and party parameters
     * are consumed by Laravel's scoped route binding so that the entry
     * is verified to belong to the requested party.
     *
     * @param  Lodge  $lodge
     * @param  StarParty  $party
     * @param  WaitlistEntry  $waitlist_entry
     * @return RedirectResponse
     */
    public function destroy(Lodge $lodge, StarParty $party, WaitlistEntry $waitlist_entry): RedirectResponse
    {
        $this->authorize('delete', $waitlist_entry);

        $waitlist_entry->delete();

        return redirect()
            ->route('star-parties::frontend.party.show', [$party->lodge, $party])
            ->with('status', 'You have left the waitlist.');
    }
}
