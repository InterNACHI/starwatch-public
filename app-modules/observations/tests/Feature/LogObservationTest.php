<?php

namespace StarWatch\Observations\Tests\Feature;

use StarWatch\Observations\Models\Observation;
use Tests\DatabaseTestCase;

class LogObservationTest extends DatabaseTestCase
{
	public function test_a_member_can_log_an_observation(): void
	{
		$user = $this->login();
		
		$payload = [
			'target' => 'M31',
			'observed_at' => now()->subHour()->format('Y-m-d H:i:s'),
			'notes' => 'Faintly visible from the back porch.',
		];
		
		$this->post(route('observations::my.observation.store'), $payload)
			->assertSessionHasNoErrors()
			->assertRedirect(route('observations::my.observation.index'));
		
		$this->assertDatabaseHas(Observation::class, [
			'user_id' => $user->getKey(),
			'target' => 'M31',
		]);
	}
	
	public function test_a_user_can_only_see_their_own_observations_on_the_index(): void
	{
		$user = $this->login();
		
		Observation::factory()->for($user)->create(['target' => 'My Observation']);
		Observation::factory()->create(['target' => 'Other User Observation']);
		
		$this->get(route('observations::my.observation.index'))
			->assertOk()
			->assertSee('My Observation')
			->assertDontSee('Other User Observation');
	}
	
	public function test_a_user_cannot_edit_someone_elses_observation(): void
	{
		$this->login();
		$other = Observation::factory()->create();
		
		$this->get(route('observations::my.observation.edit', $other))
			->assertForbidden();
	}
	
	public function test_a_user_can_destroy_their_own_observation(): void
	{
		$user = $this->login();
		$observation = Observation::factory()->for($user)->create();
		
		$this->delete(route('observations::my.observation.destroy', $observation))
			->assertRedirect(route('observations::my.observation.index'));
		
		$this->assertSoftDeleted(Observation::class, ['id' => $observation->getKey()]);
	}
}
