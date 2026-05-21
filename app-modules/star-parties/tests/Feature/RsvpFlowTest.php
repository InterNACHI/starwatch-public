<?php

namespace StarWatch\StarParties\Tests\Feature;

use StarWatch\StarParties\Enums\RsvpStatus;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;
use Tests\DatabaseTestCase;

class RsvpFlowTest extends DatabaseTestCase
{
	public function test_a_member_can_rsvp_to_an_upcoming_star_party(): void
	{
		$user = $this->login();
		$party = StarParty::factory()->create(['capacity' => 5]);
		
		$this->post(route('star-parties::my.party.rsvp.store', [$party->lodge, $party]))
			->assertSessionHasNoErrors()
			->assertRedirect(route('star-parties::frontend.party.show', [$party->lodge, $party]));
		
		$this->assertDatabaseHas(StarPartyRsvp::class, [
			'star_party_id' => $party->getKey(),
			'user_id' => $user->getKey(),
			'status' => RsvpStatus::Confirmed->value,
		]);
	}
	
	public function test_rsvp_redirects_to_the_waitlist_when_the_party_is_at_capacity(): void
	{
		$this->login();
		$party = StarParty::factory()->create(['capacity' => 2]);
		StarPartyRsvp::factory()->count(2)->for($party, 'star_party')->confirmed()->create();

		$this->post(route('star-parties::my.party.rsvp.store', [$party->lodge, $party]))
			->assertRedirect(route('star-parties::my.party.waitlist.store', [$party->lodge, $party]));

		$this->assertDatabaseCount(StarPartyRsvp::class, 2);
	}
	
	public function test_repeating_an_rsvp_does_not_create_a_duplicate(): void
	{
		$user = $this->login();
		$party = StarParty::factory()->create(['capacity' => 5]);
		
		$this->post(route('star-parties::my.party.rsvp.store', [$party->lodge, $party]));
		$this->post(route('star-parties::my.party.rsvp.store', [$party->lodge, $party]));
		
		$this->assertDatabaseCount(StarPartyRsvp::class, 1);
		$this->assertDatabaseHas(StarPartyRsvp::class, [
			'star_party_id' => $party->getKey(),
			'user_id' => $user->getKey(),
		]);
	}
}
