<?php

namespace StarWatch\StarParties\Tests\Feature;

use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;
use Tests\DatabaseTestCase;

class CancelRsvpTest extends DatabaseTestCase
{
	public function test_a_user_can_cancel_their_own_rsvp(): void
	{
		$user = $this->login();
		$party = StarParty::factory()->create();
		$rsvp = StarPartyRsvp::factory()->for($party, 'star_party')->for($user)->confirmed()->create();
		
		$this->delete(route('star-parties::my.party.rsvp.destroy', [$party->lodge, $party, $rsvp]))
			->assertSessionHasNoErrors()
			->assertRedirect(route('star-parties::frontend.party.show', [$party->lodge, $party]));
		
		$this->assertDatabaseMissing(StarPartyRsvp::class, ['id' => $rsvp->getKey()]);
	}
}
