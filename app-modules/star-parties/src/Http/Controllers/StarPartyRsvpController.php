<?php

namespace StarWatch\StarParties\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;

class StarPartyRsvpController extends Controller
{
	public function store(Request $request, Lodge $lodge, StarParty $party)
	{
		if ($party->isFull()) {
			return redirect()->route(
				'star-parties::my.party.waitlist.store',
				[$party->lodge, $party],
				307,
			);
		}

		$party->rsvps()->firstOrCreate([
			'user_id' => $request->user()->getKey(),
		]);

		return to_route('star-parties::frontend.party.show', [$party->lodge, $party])
			->with('status', "You're confirmed for this star party.");
	}
	
	public function destroy(Lodge $lodge, StarParty $party, StarPartyRsvp $rsvp)
	{
		$rsvp->delete();
		
		return to_route('star-parties::frontend.party.show', [$party->lodge, $party])
			->with('status', 'RSVP cancelled.');
	}
}
