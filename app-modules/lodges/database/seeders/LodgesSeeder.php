<?php

namespace StarWatch\Lodges\Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use StarWatch\Lodges\Models\Lodge;

class LodgesSeeder extends Seeder
{
	public function run(): void
	{
		$lodge_attributes = [
			['name' => 'Aurora Lodge', 'city' => 'Portland', 'region' => 'OR', 'blurb' => 'A friendly Pacific Northwest club that gathers at dark-sky parks year-round.'],
			['name' => 'Cascade Lodge', 'city' => 'Bend', 'region' => 'OR', 'blurb' => 'High-desert observers with a passion for deep-sky targets.'],
			['name' => 'Pleiades Lodge', 'city' => 'Boulder', 'region' => 'CO', 'blurb' => 'Front Range astronomers running regular outreach nights.'],
			['name' => 'Vela Lodge', 'city' => 'Austin', 'region' => 'TX', 'blurb' => 'Hill Country stargazers. Bring bug spray and a hot drink.'],
		];
		
		$lodges = collect($lodge_attributes)->map(fn($attrs) => Lodge::create($attrs));
		
		$aurora_organizer = User::where('email', 'aurora@starwatch.test')->first();
		$cascade_organizer = User::where('email', 'cascade@starwatch.test')->first();
		$members = User::whereIn('email', ['nova@starwatch.test', 'rigel@starwatch.test', 'sirius@starwatch.test'])->get();
		
		$aurora = $lodges->firstWhere('name', 'Aurora Lodge');
		$cascade = $lodges->firstWhere('name', 'Cascade Lodge');
		
		if ($aurora_organizer) {
			$aurora->memberships()->create([
				'user_id' => $aurora_organizer->getKey(),
				'joined_at' => now()->subMonths(8),
			]);
		}
		if ($cascade_organizer) {
			$cascade->memberships()->create([
				'user_id' => $cascade_organizer->getKey(),
				'joined_at' => now()->subMonths(4),
			]);
		}
		
		foreach ($members as $member) {
			$aurora->memberships()->create([
				'user_id' => $member->getKey(),
				'joined_at' => now()->subMonths(2),
			]);
		}
	}
}
