<?php

namespace StarWatch\StarParties\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;

class StarPartyRsvpController extends Controller
{
	public function store(Request $request, Lodge $lodge, StarParty $party)
	{
		if ($party->isFull()) {
			return to_route('star-parties::frontend.party.show', [$party->lodge, $party])
				->with('error', 'This star party is at capacity.');
		}
		
		$party->rsvps()->firstOrCreate([
			'user_id' => $request->user()->getKey(),
		]);
		
		return to_route('star-parties::frontend.party.show', [$party->lodge, $party])
			->with('status', "You're confirmed for this star party.");
	}
	
	public function destroy(Lodge $lodge, StarParty $party, StarPartyRsvp $rsvp)
	{
		DB::transaction(function() use ($party, $rsvp) {
			$rsvp->delete();
			
			$next = $party->waitlistEntries()
				->orderBy('id')
				->lockForUpdate()
				->first();
			
			if ($next) {
				$party->rsvps()->firstOrCreate(['user_id' => $next->user_id]);
				$next->delete();
			}
		});
		
		return to_route('star-parties::frontend.party.show', [$party->lodge, $party])
			->with('status', 'RSVP cancelled.');
	}
}
