<?php

namespace StarWatch\Lodges\Tests\Feature;

use StarWatch\Lodges\Models\Lodge;
use Tests\DatabaseTestCase;

class LodgeBrowsingTest extends DatabaseTestCase
{
	public function test_a_guest_can_view_the_lodge_index(): void
	{
		Lodge::factory()->count(3)->create();
		
		$this->get(route('lodges::frontend.index'))
			->assertOk()
			->assertSee('Lodges');
	}
	
	public function test_a_guest_can_view_a_lodge_show_page(): void
	{
		$lodge = Lodge::factory()->withMembers(2)->create();
		
		$this->get(route('lodges::frontend.show', $lodge))
			->assertOk()
			->assertSee($lodge->name);
	}
}
