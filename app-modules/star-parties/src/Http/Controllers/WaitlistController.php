<?php

namespace StarWatch\StarParties\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\StarParties\Http\Requests\AddToWaitlistRequest;
use StarWatch\StarParties\Http\Requests\RemoveFromWaitlistRequest;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\WaitlistEntry;
use StarWatch\StarParties\Services\WaitlistService;

final class WaitlistController extends Controller
{
    public function __construct(
        private readonly WaitlistService $service,
    ) {
    }

    /**
     * Add the current user to the waitlist for a party.
     *
     * @param  AddToWaitlistRequest  $request
     * @param  Lodge  $lodge
     * @param  StarParty  $party
     * @return RedirectResponse
     */
    public function store(AddToWaitlistRequest $request, Lodge $lodge, StarParty $party): RedirectResponse
    {
        $user = $request->user();
        $entry = $this->service->addToWaitlist($party, $user);
        $position = $this->service->getPosition($entry);

        return to_route('star-parties::frontend.party.show', [$party->lodge, $party])
            ->with('status', "You're #{$position} on the waitlist.");
    }

    /**
     * Remove (cancel) the user's waitlist entry.
     *
     * @param  RemoveFromWaitlistRequest  $request
     * @param  Lodge  $lodge
     * @param  StarParty  $party
     * @param  WaitlistEntry  $entry
     * @return RedirectResponse
     */
    public function destroy(RemoveFromWaitlistRequest $request, Lodge $lodge, StarParty $party, WaitlistEntry $entry): RedirectResponse
    {
        $this->service->removeFromWaitlist($entry);

        return to_route('star-parties::frontend.party.show', [$party->lodge, $party])
            ->with('status', 'You have left the waitlist.');
    }
}
