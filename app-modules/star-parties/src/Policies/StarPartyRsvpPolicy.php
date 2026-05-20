<?php

namespace StarWatch\StarParties\Policies;

use App\User;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;

class StarPartyRsvpPolicy
{
	public function create(User $user, StarParty $party): bool
	{
		return $party->scheduled_at->isFuture() && ! $party->hasRsvpFrom($user);
	}
	
	public function delete(User $user, StarPartyRsvp $rsvp): bool
	{
		return true; // TODO
	}
}
