<?php

namespace StarWatch\StarParties\Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use StarWatch\StarParties\Enums\RsvpStatus;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;

class StarPartyRsvpFactory extends Factory
{
	protected $model = StarPartyRsvp::class;
	
	public function definition(): array
	{
		return [
			'star_party_id' => StarParty::factory(),
			'user_id' => User::factory(),
			'status' => RsvpStatus::Confirmed,
		];
	}
	
	public function confirmed(): static
	{
		return $this->state(fn() => ['status' => RsvpStatus::Confirmed]);
	}
}
