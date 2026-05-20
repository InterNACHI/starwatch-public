<?php

use Illuminate\Support\Facades\Route;
use StarWatch\StarParties\Http\Controllers\StarPartyController;
use StarWatch\StarParties\Http\Controllers\StarPartyRsvpController;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\StarPartyRsvp;

Route::middleware('web')
	->name('star-parties::frontend.party.')
	->group(function() {
		Route::get('/lodges/{lodge}/star-parties', [StarPartyController::class, 'index'])
			->name('index')
			->breadcrumb('Star parties', 'lodges::frontend.show');
		Route::get('/lodges/{lodge}/star-parties/{party}', [StarPartyController::class, 'show'])
			->scopeBindings()
			->name('show')
			->breadcrumb(fn($_, StarParty $p) => $p->title, 'star-parties::frontend.party.index');
	});

Route::middleware(['web', 'auth'])
	->name('star-parties::my.party.')
	->prefix('my/lodges/{lodge}/star-parties')
	->scopeBindings()
	->group(function() {
		Route::get('/create', [StarPartyController::class, 'create'])
			->name('create')
			->can('create', [StarParty::class, 'lodge'])
			->breadcrumb('New star party', 'star-parties::frontend.party.index');
		Route::post('/', [StarPartyController::class, 'store'])
			->name('store')
			->can('create', [StarParty::class, 'lodge']);
		Route::get('/{party}/edit', [StarPartyController::class, 'edit'])
			->name('edit')
			->can('update', 'party')
			->breadcrumb('Edit', 'star-parties::frontend.party.show');
		Route::put('/{party}', [StarPartyController::class, 'update'])
			->name('update')
			->can('update', 'party');
		Route::delete('/{party}', [StarPartyController::class, 'destroy'])
			->name('destroy')
			->can('delete', 'party');
		
		Route::post('/{party}/rsvp', [StarPartyRsvpController::class, 'store'])
			->name('rsvp.store')
			->can('create', [StarPartyRsvp::class, 'party']);
		Route::delete('/{party}/rsvp/{rsvp}', [StarPartyRsvpController::class, 'destroy'])
			->name('rsvp.destroy')
			->can('delete', 'rsvp');
	});
