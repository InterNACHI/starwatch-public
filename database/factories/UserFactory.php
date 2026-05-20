<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
	protected static ?string $password = null;
	
	protected $model = User::class;
	
	public function definition(): array
	{
		return [
			'name' => $this->faker->name(),
			'email' => $this->faker->unique()->safeEmail(),
			'email_verified_at' => now(),
			'password' => static::$password ??= Hash::make('password'),
			'remember_token' => Str::random(10),
			'role' => UserRole::Member,
		];
	}
	
	public function member(): static
	{
		return $this->state(fn() => ['role' => UserRole::Member]);
	}
	
	public function organizer(): static
	{
		return $this->state(fn() => ['role' => UserRole::Organizer]);
	}
	
	public function admin(): static
	{
		return $this->state(fn() => ['role' => UserRole::Admin]);
	}
	
	public function unverified(): static
	{
		return $this->state(fn() => ['email_verified_at' => null]);
	}
}
