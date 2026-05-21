<?php

namespace StarWatch\StarParties\Tests\Feature;

use App\User;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;
use StarWatch\StarParties\Models\WaitlistEntry;
use Tests\DatabaseTestCase;

class WaitlistTest extends DatabaseTestCase
{
    public function test_a_member_can_join_the_waitlist_when_a_party_is_full(): void
    {
        $this->login();
        $party = $this->fullParty();

        $this->post(route('star-parties::my.party.waitlist.store', [$party->lodge, $party]))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('star-parties::frontend.party.show', [$party->lodge, $party]));

        $this->assertDatabaseCount(WaitlistEntry::class, 1);
    }

    public function test_a_member_cannot_join_the_waitlist_when_seats_are_still_available(): void
    {
        $this->login();
        $party = StarParty::factory()->create(['capacity' => 5]);

        $this->post(route('star-parties::my.party.waitlist.store', [$party->lodge, $party]))
            ->assertForbidden();

        $this->assertDatabaseCount(WaitlistEntry::class, 0);
    }

    public function test_a_member_can_leave_the_waitlist(): void
    {
        $user = $this->login();
        $party = $this->fullParty();
        $entry = WaitlistEntry::factory()->for($party, 'starParty')->for($user)->create();

        $this->delete(route('star-parties::my.party.waitlist.destroy', [$party->lodge, $party, $entry]))
            ->assertRedirect(route('star-parties::frontend.party.show', [$party->lodge, $party]));

        $this->assertDatabaseMissing(WaitlistEntry::class, ['id' => $entry->getKey()]);
    }

    public function test_a_member_cannot_remove_someone_elses_waitlist_entry(): void
    {
        $this->login();
        $party = $this->fullParty();
        $entry = WaitlistEntry::factory()->for($party, 'starParty')->create();

        $this->delete(route('star-parties::my.party.waitlist.destroy', [$party->lodge, $party, $entry]))
            ->assertForbidden();

        $this->assertDatabaseHas(WaitlistEntry::class, ['id' => $entry->getKey()]);
    }

    public function test_cancelling_an_rsvp_promotes_the_first_waitlist_entry(): void
    {
        $rsvp_user = $this->login();
        $party = StarParty::factory()->create(['capacity' => 1]);
        $rsvp = StarPartyRsvp::factory()->for($party, 'star_party')->for($rsvp_user)->confirmed()->create();

        $first_waiting = User::factory()->member()->create();
        $second_waiting = User::factory()->member()->create();
        WaitlistEntry::factory()->for($party, 'starParty')->for($first_waiting)->create();
        WaitlistEntry::factory()->for($party, 'starParty')->for($second_waiting)->create();

        $this->delete(route('star-parties::my.party.rsvp.destroy', [$party->lodge, $party, $rsvp]));

        $this->assertDatabaseHas(StarPartyRsvp::class, [
            'star_party_id' => $party->getKey(),
            'user_id' => $first_waiting->getKey(),
        ]);
        $this->assertDatabaseMissing(WaitlistEntry::class, ['user_id' => $first_waiting->getKey()]);
        $this->assertDatabaseHas(WaitlistEntry::class, ['user_id' => $second_waiting->getKey()]);
    }

    public function test_cancelling_an_rsvp_with_no_waitlist_just_frees_the_seat(): void
    {
        $user = $this->login();
        $party = StarParty::factory()->create(['capacity' => 2]);
        $rsvp = StarPartyRsvp::factory()->for($party, 'star_party')->for($user)->confirmed()->create();

        $this->delete(route('star-parties::my.party.rsvp.destroy', [$party->lodge, $party, $rsvp]));

        $this->assertDatabaseMissing(StarPartyRsvp::class, ['id' => $rsvp->getKey()]);
    }

    public function test_position_reflects_join_order(): void
    {
        $party = $this->fullParty();
        $first = WaitlistEntry::factory()->for($party, 'starParty')->create();
        $second = WaitlistEntry::factory()->for($party, 'starParty')->create();
        $third = WaitlistEntry::factory()->for($party, 'starParty')->create();

        $this->assertSame(1, $first->position());
        $this->assertSame(2, $second->position());
        $this->assertSame(3, $third->position());
    }

    private function fullParty(int $capacity = 2): StarParty
    {
        $party = StarParty::factory()->create(['capacity' => $capacity]);
        StarPartyRsvp::factory()
            ->count($capacity)
            ->for($party, 'star_party')
            ->confirmed()
            ->create();

        return $party;
    }
}
