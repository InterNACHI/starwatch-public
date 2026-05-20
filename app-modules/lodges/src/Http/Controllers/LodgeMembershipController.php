<?php

namespace StarWatch\Lodges\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use StarWatch\Lodges\Models\Lodge;

class LodgeMembershipController extends Controller
{
	public function store(Request $request, Lodge $lodge)
	{
		$lodge->memberships()->firstOrCreate([
			'user_id' => $request->user()->getKey(),
		], [
			'joined_at' => now(),
		]);
		
		return to_route('lodges::frontend.show', $lodge)
			->with('status', "You joined {$lodge->name}.");
	}
	
	public function destroy(Request $request, Lodge $lodge)
	{
		$lodge->memberships()
			->where('user_id', $request->user()->getKey())
			->delete();
		
		return to_route('lodges::frontend.show', $lodge)
			->with('status', "You left {$lodge->name}.");
	}
}
