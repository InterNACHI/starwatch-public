<?php

namespace StarWatch\StarParties\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\StarParties\Enums\RsvpStatus;
use StarWatch\StarParties\Http\Requests\StoreStarPartyRequest;
use StarWatch\StarParties\Http\Requests\UpdateStarPartyRequest;
use StarWatch\StarParties\Models\StarParty;

class StarPartyController extends Controller
{
	public function index(Lodge $lodge)
	{
		return view('star-parties::index', [
			'lodge' => $lodge,
			'parties' => $lodge->parties()
				->where('scheduled_at', '>=', now())
				->withCount(['rsvps as confirmed_count' => fn($q) => $q->where('status', RsvpStatus::Confirmed)])
				->orderBy('scheduled_at')
				->paginate(),
		]);
	}
	
	public function create(Lodge $lodge)
	{
		return view('star-parties::create', ['lodge' => $lodge]);
	}

	public function store(StoreStarPartyRequest $request, Lodge $lodge)
	{
		$party = $lodge->parties()->create($request->validated());

		return to_route('star-parties::frontend.party.show', [$party->lodge, $party])
			->with('status', 'Star party created.');
	}

	public function show(Lodge $lodge, StarParty $party)
	{
		$party->load('lodge');

		return view('star-parties::show', [
			'party' => $party,
			'confirmed_count' => $party->confirmed_rsvps()->count(),
			'user_rsvp' => Auth::check()
				? $party->rsvps()->where('user_id', Auth::id())->first()
				: null,
		]);
	}
	
	public function edit(Lodge $lodge, StarParty $party)
	{
		return view('star-parties::edit', ['party' => $party]);
	}
	
	public function update(UpdateStarPartyRequest $request, Lodge $lodge, StarParty $party)
	{
		$party->update($request->validated());
		
		return to_route('star-parties::frontend.party.show', [$party->lodge, $party])
			->with('status', 'Star party updated.');
	}
	
	public function destroy(Lodge $lodge, StarParty $party)
	{
		$party->delete();
		
		return to_route('lodges::frontend.show', $lodge)
			->with('status', 'Star party cancelled.');
	}
}
