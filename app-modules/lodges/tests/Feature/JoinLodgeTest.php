<?php

namespace StarWatch\Lodges\Tests\Feature;

use StarWatch\Lodges\Models\Lodge;
use StarWatch\Lodges\Models\LodgeMember;
use Tests\DatabaseTestCase;

class JoinLodgeTest extends DatabaseTestCase
{
	public function test_a_member_can_join_a_lodge(): void
	{
		$user = $this->login();
		$lodge = Lodge::factory()->create();
		
		$this->post(route('lodges::my.join.store', $lodge))
			->assertSessionHasNoErrors()
			->assertRedirect(route('lodges::frontend.show', $lodge));
		
		$this->assertDatabaseHas(LodgeMember::class, [
			'lodge_id' => $lodge->getKey(),
			'user_id' => $user->getKey(),
		]);
	}
	
	public function test_a_guest_cannot_join_a_lodge(): void
	{
		$lodge = Lodge::factory()->create();
		
		$this->post(route('lodges::my.join.store', $lodge))
			->assertRedirect(route('login'));
		
		$this->assertEquals(0, LodgeMember::count());
	}
	
	public function test_a_member_can_leave_a_lodge(): void
	{
		$user = $this->login();
		$lodge = Lodge::factory()->withMember($user)->create();
		
		$this->delete(route('lodges::my.leave.destroy', $lodge))
			->assertSessionHasNoErrors()
			->assertRedirect(route('lodges::frontend.show', $lodge));
		
		$this->assertDatabaseMissing(LodgeMember::class, [
			'lodge_id' => $lodge->getKey(),
			'user_id' => $user->getKey(),
		]);
	}
}
