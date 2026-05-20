<?php

namespace StarWatch\Observations\Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use StarWatch\Observations\Models\Observation;

class ObservationFactory extends Factory
{
	protected $model = Observation::class;
	
	public function definition(): array
	{
		$targets = [
			'M31 — Andromeda Galaxy',
			'M42 — Orion Nebula',
			'M45 — Pleiades',
			'Jupiter',
			'Saturn',
			'Mars opposition',
			'Albireo (double star)',
			'NGC 869 — Double Cluster',
			'Comet C/2023 A3',
			'Iridium flare',
		];
		
		return [
			'user_id' => User::factory(),
			'lodge_id' => null,
			'target' => $this->faker->randomElement($targets),
			'observed_at' => $this->faker->dateTimeBetween('-90 days', 'now'),
			'notes' => $this->faker->optional()->sentence(8),
		];
	}
}
