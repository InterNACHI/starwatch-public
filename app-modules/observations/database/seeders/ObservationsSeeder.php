<?php

namespace StarWatch\Observations\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\Observations\Models\Observation;

class ObservationsSeeder extends Seeder
{
	public function run(): void
	{
		$users = User::all();
		$lodges = Lodge::all();
		
		foreach ($users as $user) {
			$count = random_int(2, 5);
			for ($i = 0; $i < $count; $i++) {
				Observation::factory()->for($user)->create([
					'lodge_id' => random_int(0, 1) ? $lodges->random()->getKey() : null,
				]);
			}
		}
	}
}
