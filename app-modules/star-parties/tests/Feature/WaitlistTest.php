<?php

namespace StarWatch\StarParties\Tests\Feature;

use App\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\StarParties\Enums\RsvpStatus;
use StarWatch\StarParties\Enums\WaitlistEntryStatus;
use StarWatch\StarParties\Events\MemberJoinedWaitlist;
use StarWatch\StarParties\Events\MemberPromotedFromWaitlist;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;
use StarWatch\StarParties\Models\WaitlistEntry;
use StarWatch\StarParties\Services\WaitlistService;
use Tests\DatabaseTestCase;

final class WaitlistTest extends DatabaseTestCase
{
    /** @test */
    public function itAddsAUserToTheWaitlistWhenTheStarPartyIsFull(): void
    {
        Event::fake([MemberJoinedWaitlist::class, MemberPromotedFromWaitlist::class]);
        Bus::fake();

        $user = $this->login();
        $lodge = Lodge::factory()->create();
        $party = StarParty::factory()->create([
            'lodge_id' => $lodge->id,
            'capacity' => 2,
            'scheduled_at' => now()->addDays(7),
        ]);

        StarPartyRsvp::factory()->count(2)->for($party, 'star_party')->confirmed()->create();

        $this->post(route('star-parties::my.party.rsvp.store', [$party->lodge, $party]))
            ->assertRedirect(route('star-parties::my.party.waitlist.store', [$party->lodge, $party]));

        $this->post(route('star-parties::my.party.waitlist.store', [$party->lodge, $party]))
            ->assertRedirect(route('star-parties::frontend.party.show', [$party->lodge, $party]));

        $this->assertDatabaseHas(WaitlistEntry::class, [
            'star_party_id' => $party->getKey(),
            'user_id' => $user->getKey(),
            'position' => 1,
            'status' => WaitlistEntryStatus::Waiting->value,
        ]);

        Event::assertDispatched(MemberJoinedWaitlist::class, function(MemberJoinedWaitlist $event) use ($user, $party) {
            return $event->entry->user_id === $user->getKey()
                && $event->entry->star_party_id === $party->getKey();
        });
    }

    /** @test */
    public function itDeniesJoiningTheWaitlistWhenSpotsAreAvailable(): void
    {
        $this->login();
        $lodge = Lodge::factory()->create();
        $party = StarParty::factory()->create([
            'lodge_id' => $lodge->id,
            'capacity' => 5,
            'scheduled_at' => now()->addDays(7),
        ]);

        $this->post(route('star-parties::my.party.waitlist.store', [$party->lodge, $party]))
            ->assertForbidden();

        $this->assertDatabaseCount(WaitlistEntry::class, 0);
    }

    /** @test */
    public function itPromotesTheFirstWaitlistEntryWhenAConfirmedRsvpIsCancelled(): void
    {
        Event::fake([MemberJoinedWaitlist::class, MemberPromotedFromWaitlist::class]);

        $organizer = User::factory()->member()->create();
        $waitingUser = User::factory()->member()->create();
        $lodge = Lodge::factory()->create();
        $party = StarParty::factory()->create([
            'lodge_id' => $lodge->id,
            'capacity' => 2,
            'scheduled_at' => now()->addDays(7),
        ]);

        $organizerRsvp = StarPartyRsvp::factory()->for($party, 'star_party')->for($organizer)->confirmed()->create();
        StarPartyRsvp::factory()->for($party, 'star_party')->confirmed()->create();

        WaitlistEntry::factory()->create([
            'star_party_id' => $party->id,
            'user_id' => $waitingUser->id,
            'position' => 1,
            'status' => WaitlistEntryStatus::Waiting,
            'joined_at' => now()->subMinute(),
        ]);

        $this->actingAs($organizer);

        $this->delete(route('star-parties::my.party.rsvp.destroy', [$party->lodge, $party, $organizerRsvp]))
            ->assertRedirect(route('star-parties::frontend.party.show', [$party->lodge, $party]));

        $this->assertDatabaseHas(StarPartyRsvp::class, [
            'star_party_id' => $party->getKey(),
            'user_id' => $waitingUser->getKey(),
            'status' => RsvpStatus::Confirmed->value,
        ]);

        $this->assertDatabaseHas(WaitlistEntry::class, [
            'star_party_id' => $party->getKey(),
            'user_id' => $waitingUser->getKey(),
            'status' => WaitlistEntryStatus::Promoted->value,
        ]);

        Event::assertDispatched(MemberPromotedFromWaitlist::class);
    }

    /** @test */
    public function itResequencesPositionsAfterAWaitingEntryIsRemoved(): void
    {
        $lodge = Lodge::factory()->create();
        $party = StarParty::factory()->create([
            'lodge_id' => $lodge->id,
            'capacity' => 1,
            'scheduled_at' => now()->addDays(7),
        ]);

        StarPartyRsvp::factory()->for($party, 'star_party')->confirmed()->create();

        $first = User::factory()->member()->create();
        $second = User::factory()->member()->create();
        $third = User::factory()->member()->create();

        $firstEntry = WaitlistEntry::factory()->create([
            'star_party_id' => $party->id,
            'user_id' => $first->id,
            'position' => 1,
            'status' => WaitlistEntryStatus::Waiting,
            'joined_at' => now()->subMinutes(3),
        ]);
        $secondEntry = WaitlistEntry::factory()->create([
            'star_party_id' => $party->id,
            'user_id' => $second->id,
            'position' => 2,
            'status' => WaitlistEntryStatus::Waiting,
            'joined_at' => now()->subMinutes(2),
        ]);
        $thirdEntry = WaitlistEntry::factory()->create([
            'star_party_id' => $party->id,
            'user_id' => $third->id,
            'position' => 3,
            'status' => WaitlistEntryStatus::Waiting,
            'joined_at' => now()->subMinute(),
        ]);

        $service = $this->app->make(WaitlistService::class);

        $this->assertSame(1, $service->getPosition($firstEntry));
        $this->assertSame(2, $service->getPosition($secondEntry));
        $this->assertSame(3, $service->getPosition($thirdEntry));

        $this->actingAs($second);
        $this->delete(route('star-parties::my.party.waitlist.destroy', [$party->lodge, $party, $secondEntry]))
            ->assertRedirect(route('star-parties::frontend.party.show', [$party->lodge, $party]));

        $firstEntry->refresh();
        $thirdEntry->refresh();

        $this->assertSame(1, $service->getPosition($firstEntry));
        $this->assertSame(2, $service->getPosition($thirdEntry));

        $this->assertDatabaseHas(WaitlistEntry::class, [
            'id' => $secondEntry->id,
            'status' => WaitlistEntryStatus::Cancelled->value,
        ]);
    }

    /** @test */
    public function itFiresEventsWhenJoiningAndBeingPromoted(): void
    {
        Event::fake([MemberJoinedWaitlist::class, MemberPromotedFromWaitlist::class]);

        $organizer = User::factory()->member()->create();
        $joiningUser = User::factory()->member()->create();
        $lodge = Lodge::factory()->create();
        $party = StarParty::factory()->create([
            'lodge_id' => $lodge->id,
            'capacity' => 1,
            'scheduled_at' => now()->addDays(7),
        ]);

        $organizerRsvp = StarPartyRsvp::factory()->for($party, 'star_party')->for($organizer)->confirmed()->create();

        $this->actingAs($joiningUser);
        $this->post(route('star-parties::my.party.waitlist.store', [$party->lodge, $party]))
            ->assertRedirect(route('star-parties::frontend.party.show', [$party->lodge, $party]));

        Event::assertDispatched(MemberJoinedWaitlist::class);
        Event::assertNotDispatched(MemberPromotedFromWaitlist::class);

        $this->actingAs($organizer);
        $this->delete(route('star-parties::my.party.rsvp.destroy', [$party->lodge, $party, $organizerRsvp]))
            ->assertRedirect(route('star-parties::frontend.party.show', [$party->lodge, $party]));

        Event::assertDispatched(MemberPromotedFromWaitlist::class);
    }
}
