<?php

namespace StarWatch\StarParties\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;

class StarPartyFactory extends Factory
{
	protected $model = StarParty::class;
	
	public function definition(): array
	{
		$titles = [
			'New moon dark-sky outing',
			'Beginner astrophotography night',
			'Mars opposition viewing',
			'Perseid meteor watch',
			'Public outreach evening',
		];
		
		$locations = [
			'Larch Mountain Pullout',
			'Pine Ridge Observatory',
			'Westside Park',
			'Sky Meadows State Park',
		];
		
		return [
			'lodge_id' => Lodge::factory(),
			'title' => $this->faker->randomElement($titles),
			'scheduled_at' => $this->faker->dateTimeBetween('+2 days', '+60 days'),
			'location' => $this->faker->randomElement($locations),
			'capacity' => $this->faker->numberBetween(8, 30),
		];
	}
	
	public function atCapacity(): static
	{
		return $this->afterCreating(function(StarParty $party) {
			StarPartyRsvp::factory()
				->for($party)
				->count($party->capacity)
				->confirmed()
				->create();
		});
	}
	
	public function inPast(): static
	{
		return $this->state(fn() => [
			'scheduled_at' => $this->faker->dateTimeBetween('-30 days', '-1 day'),
		]);
	}
}
