<?php

namespace StarWatch\Lodges\Policies;

use App\User;
use StarWatch\Lodges\Models\Lodge;

class LodgePolicy
{
	public function viewAny(?User $user): bool
	{
		return true;
	}
	
	public function view(?User $user, Lodge $lodge): bool
	{
		return true;
	}
	
	public function join(User $user, Lodge $lodge): bool
	{
		return ! $lodge->hasMember($user);
	}
	
	public function leave(User $user, Lodge $lodge): bool
	{
		return $lodge->hasMember($user);
	}
}
