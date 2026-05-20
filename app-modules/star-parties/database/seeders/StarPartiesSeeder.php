<?php

namespace StarWatch\StarParties\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use StarWatch\Lodges\Models\Lodge;

class StarPartiesSeeder extends Seeder
{
	public function run(): void
	{
		$lodges = Lodge::all()->keyBy('name');
		$members = User::all();
		
		$parties = [
			['lodge' => 'Aurora Lodge', 'title' => 'New moon dark-sky outing', 'days' => 3, 'capacity' => 8, 'fill' => 8],
			['lodge' => 'Aurora Lodge', 'title' => 'Beginner astrophotography night', 'days' => 14, 'capacity' => 20, 'fill' => 8],
			['lodge' => 'Cascade Lodge', 'title' => 'Mars opposition viewing', 'days' => 21, 'capacity' => 15, 'fill' => 5],
			['lodge' => 'Cascade Lodge', 'title' => 'Lunar craters workshop', 'days' => 35, 'capacity' => 10, 'fill' => 3],
			['lodge' => 'Pleiades Lodge', 'title' => 'Perseid meteor watch', 'days' => 45, 'capacity' => 30, 'fill' => 14],
			['lodge' => 'Vela Lodge', 'title' => 'Public outreach evening', 'days' => 55, 'capacity' => 25, 'fill' => 9],
		];
		
		foreach ($parties as $config) {
			if (! $lodge = $lodges->get($config['lodge'])) {
				continue;
			}
			
			$party = $lodge->parties()->create([
				'title' => $config['title'],
				'scheduled_at' => now()->addDays($config['days'])->setTime(21, 0),
				'location' => 'Lat 45.2 Long -121.7 — pullout #4',
				'capacity' => $config['capacity'],
			]);
			
			$members
				->random(min($config['fill'], $members->count()))
				->each(fn(User $user) => $party->rsvps()->firstOrCreate([
					'user_id' => $user->getKey(),
				]));
		}
	}
}
