<?php

namespace StarWatch\Observations\Policies;

use App\User;
use StarWatch\Observations\Models\Observation;

class ObservationPolicy
{
	public function viewAny(User $user): bool
	{
		return true;
	}
	
	public function view(User $user, Observation $observation): bool
	{
		return $observation->user()->is($user) || $user->isAdmin();
	}
	
	public function create(User $user): bool
	{
		return true;
	}
	
	public function update(User $user, Observation $observation): bool
	{
		return $this->view($user, $observation);
	}
	
	public function delete(User $user, Observation $observation): bool
	{
		return $this->view($user, $observation);
	}
}
