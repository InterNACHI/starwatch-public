<?php

namespace Database\Seeders;

use App\User;
use Illuminate\Database\Seeder;
use StarWatch\Lodges\Database\Seeders\LodgesSeeder;
use StarWatch\Observations\Database\Seeders\ObservationsSeeder;
use StarWatch\StarParties\Database\Seeders\StarPartiesSeeder;

class DatabaseSeeder extends Seeder
{
	public function run(): void
	{
		User::factory()->admin()->create([
			'name' => 'Ada Admin',
			'email' => 'admin@starwatch.test',
		]);
		
		User::factory()->organizer()->create([
			'name' => 'Aurora Banks',
			'email' => 'aurora@starwatch.test',
		]);
		
		User::factory()->organizer()->create([
			'name' => 'Cascade Reyes',
			'email' => 'cascade@starwatch.test',
		]);
		
		User::factory()->member()->create([
			'name' => 'Nova Yamamoto',
			'email' => 'nova@starwatch.test',
		]);
		
		foreach (['rigel', 'sirius', 'capella', 'arcturus', 'altair', 'deneb', 'spica'] as $name) {
			User::factory()->member()->create([
				'name' => ucfirst($name).' Member',
				'email' => "{$name}@starwatch.test",
			]);
		}
		
		$this->call([
			LodgesSeeder::class,
			ObservationsSeeder::class,
			StarPartiesSeeder::class,
		]);
	}
}
