<?php

namespace StarWatch\Lodges\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\StarParties\Enums\RsvpStatus;

class LodgeController extends Controller
{
	public function index(): View
	{
		return view('lodges::index', [
			'lodges' => Lodge::query()
				->withCount('memberships')
				->orderBy('name')
				->paginate(),
		]);
	}
	
	public function show(Lodge $lodge): View
	{
		$lodge->load(['members' => fn(Builder $q) => $q->orderBy('name')]);
		
		return view('lodges::show', [
			'lodge' => $lodge,
			'upcoming_parties' => $lodge->parties()
				->where('scheduled_at', '>=', now())
				->withCount(['rsvps as confirmed_count' => fn(Builder $q) => $q->where('status', RsvpStatus::Confirmed)])
				->orderBy('scheduled_at')
				->take(10)
				->get(),
			'is_member' => $lodge->hasMember(user()),
		]);
	}
}
