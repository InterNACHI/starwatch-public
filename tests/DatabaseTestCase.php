<?php

namespace Tests;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class DatabaseTestCase extends TestCase
{
	use RefreshDatabase;
	
	public function login(?Authenticatable $user = null): User
	{
		$user ??= User::factory()->member()->create();
		
		$this->actingAs($user);
		
		return $user;
	}
}
