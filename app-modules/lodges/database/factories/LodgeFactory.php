<?php

namespace StarWatch\Lodges\Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use StarWatch\Lodges\Models\Lodge;

class LodgeFactory extends Factory
{
	protected $model = Lodge::class;
	
	public function definition(): array
	{
		$constellation = $this->faker->unique()->randomElement([
			'Orion',
			'Lyra',
			'Cassiopeia',
			'Andromeda',
			'Cygnus',
			'Aurora',
			'Cascade',
			'Perseus',
			'Pleiades',
			'Vela',
		]);
		
		return [
			'name' => $constellation.' Lodge',
			'city' => $this->faker->city(),
			'region' => $this->faker->stateAbbr(),
			'blurb' => $this->faker->sentence(12),
		];
	}
	
	public function withMembers(int $count = 3): static
	{
		return $this->afterCreating(function(Lodge $lodge) use ($count) {
			User::factory()->count($count)->create()->each(function(User $user) use ($lodge) {
				$lodge->memberships()->create([
					'user_id' => $user->getKey(),
					'joined_at' => now(),
				]);
			});
		});
	}
	
	public function withMember(?User $user = null): static
	{
		return $this->afterCreating(function(Lodge $lodge) use ($user) {
			$lodge->memberships()->create([
				'user_id' => ($user ?? User::factory()->create())->getKey(),
				'joined_at' => now(),
			]);
		});
	}
}
