<?php

namespace StarWatch\StarParties\Tests\Feature;

use StarWatch\Lodges\Models\Lodge;
use StarWatch\StarParties\Models\StarParty;
use Tests\DatabaseTestCase;

class StarPartyAccessTest extends DatabaseTestCase
{
	public function test_a_guest_can_view_a_star_party(): void
	{
		$party = StarParty::factory()->create();
		
		$this->get(route('star-parties::frontend.party.show', [$party->lodge, $party]))
			->assertOk()
			->assertSee($party->title);
	}
	
	public function test_a_guest_cannot_create_a_star_party(): void
	{
		$lodge = Lodge::factory()->create();
		
		$this->get(route('star-parties::my.party.create', $lodge))
			->assertRedirect(route('login'));
	}
}
