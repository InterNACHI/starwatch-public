<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\Lodges\Policies\LodgePolicy;
use StarWatch\Observations\Models\Observation;
use StarWatch\Observations\Policies\ObservationPolicy;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;
use StarWatch\StarParties\Policies\StarPartyPolicy;
use StarWatch\StarParties\Policies\StarPartyRsvpPolicy;

class AuthServiceProvider extends ServiceProvider
{
	protected $policies = [
		Lodge::class => LodgePolicy::class,
		Observation::class => ObservationPolicy::class,
		StarParty::class => StarPartyPolicy::class,
		StarPartyRsvp::class => StarPartyRsvpPolicy::class,
	];
}
