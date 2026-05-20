<?php

namespace StarWatch\StarParties\Policies;

use App\User;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\StarParties\Models\StarParty;

class StarPartyPolicy
{
	public function before(User $user, string $ability): ?true
	{
		return $user->isAdmin() ? true : null;
	}
	
	public function viewAny(?User $user): bool
	{
		return true;
	}
	
	public function view(?User $user, StarParty $party): bool
	{
		return true;
	}
	
	public function create(User $user, Lodge $lodge): bool
	{
		return $user->isOrganizer() && $lodge->hasMember($user);
	}
	
	public function update(User $user, StarParty $party): bool
	{
		return $this->create($user, $party->lodge);
	}
	
	public function delete(User $user, StarParty $party): bool
	{
		return $this->update($user, $party);
	}
}
