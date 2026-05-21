<?php

namespace StarWatch\StarParties\Tests\Feature;

use App\User;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;
use StarWatch\StarParties\Models\WaitlistEntry;
use Tests\DatabaseTestCase;

class WaitlistTest extends DatabaseTestCase
{
    /**
     * A signed-in member should be able to join the waitlist of a
     * star party that has already reached its capacity.
     *
     * @test
     * @return void
     */
    public function a_member_can_join_the_waitlist_when_a_party_is_full(): void
    {
        $this->login();
        $party = $this->fullParty();

        $this->post(route('star-parties::my.party.waitlist.store', [$party->lodge, $party]))
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('star-parties::frontend.party.show', [$party->lodge, $party]));

        $this->assertDatabaseCount(WaitlistEntry::class, 1);
    }

    /**
     * The waitlist is only meaningful once a party is full; joining
     * the waitlist should be denied while seats are still available.
     *
     * @test
     * @return void
     */
    public function a_member_cannot_join_the_waitlist_when_seats_are_still_available(): void
    {
        $this->login();
        $party = StarParty::factory()->create(['capacity' => 5]);

        $this->post(route('star-parties::my.party.waitlist.store', [$party->lodge, $party]))
            ->assertForbidden();

        $this->assertDatabaseCount(WaitlistEntry::class, 0);
    }

    /**
     * A member should be able to cancel their own waitlist entry.
     *
     * @test
     * @return void
     */
    public function a_member_can_leave_the_waitlist(): void
    {
        $user = $this->login();
        $party = $this->fullParty();
        $entry = WaitlistEntry::factory()->for($party, 'starParty')->for($user)->create();

        $this->delete(route('star-parties::my.party.waitlist.destroy', [$party->lodge, $party, $entry]))
            ->assertRedirect(route('star-parties::frontend.party.show', [$party->lodge, $party]));

        $this->assertDatabaseMissing(WaitlistEntry::class, ['id' => $entry->id]);
    }

    /**
     * Members should not be able to remove a waitlist entry that
     * belongs to another user.
     *
     * @test
     * @return void
     */
    public function a_member_cannot_remove_someone_elses_waitlist_entry(): void
    {
        $this->login();
        $party = $this->fullParty();
        $entry = WaitlistEntry::factory()->for($party, 'starParty')->create();

        $this->delete(route('star-parties::my.party.waitlist.destroy', [$party->lodge, $party, $entry]))
            ->assertForbidden();

        $this->assertDatabaseHas(WaitlistEntry::class, ['id' => $entry->id]);
    }

    /**
     * When a confirmed RSVP is cancelled, the first member on the
     * waitlist should be moved into a confirmed RSVP and removed
     * from the waitlist.
     *
     * @test
     * @return void
     */
    public function cancelling_an_rsvp_promotes_the_first_waitlist_entry(): void
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
            'star_party_id' => $party->id,
            'user_id' => $first_waiting->id,
        ]);
        $this->assertDatabaseMissing(WaitlistEntry::class, ['user_id' => $first_waiting->id]);
        $this->assertDatabaseHas(WaitlistEntry::class, ['user_id' => $second_waiting->id]);
    }

    /**
     * Cancelling an RSVP on a party that has nobody waiting should
     * simply free the seat with no promotion.
     *
     * @test
     * @return void
     */
    public function cancelling_an_rsvp_with_no_waitlist_just_frees_the_seat(): void
    {
        $user = $this->login();
        $party = StarParty::factory()->create(['capacity' => 2]);
        $rsvp = StarPartyRsvp::factory()->for($party, 'star_party')->for($user)->confirmed()->create();

        $this->delete(route('star-parties::my.party.rsvp.destroy', [$party->lodge, $party, $rsvp]));

        $this->assertDatabaseMissing(StarPartyRsvp::class, ['id' => $rsvp->id]);
    }

    /**
     * Waitlist position should be derived from join order: the
     * first entry should be position 1, the second position 2,
     * and so on.
     *
     * @test
     * @return void
     */
    public function position_reflects_join_order(): void
    {
        $party = $this->fullParty();
        $first = WaitlistEntry::factory()->for($party, 'starParty')->create();
        $second = WaitlistEntry::factory()->for($party, 'starParty')->create();
        $third = WaitlistEntry::factory()->for($party, 'starParty')->create();

        $this->assertSame(1, $first->position());
        $this->assertSame(2, $second->position());
        $this->assertSame(3, $third->position());
    }

    /**
     * Helper that creates a star party with all seats already
     * filled by confirmed RSVPs.
     *
     * @param  int  $capacity
     * @return StarParty
     */
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
