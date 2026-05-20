<?php

use Illuminate\Support\Facades\Route;
use StarWatch\Lodges\Http\Controllers\LodgeController;
use StarWatch\Lodges\Http\Controllers\LodgeMembershipController;
use StarWatch\Lodges\Models\Lodge;

Route::middleware('web')
	->prefix('lodges')
	->name('lodges::frontend.')
	->group(function() {
		Route::get('/', [LodgeController::class, 'index'])
			->name('index')
			->breadcrumb('Lodges', 'home');
		Route::get('/{lodge}', [LodgeController::class, 'show'])
			->name('show')
			->breadcrumb(fn(Lodge $lodge) => $lodge->name, '.index');
	});

Route::middleware(['web', 'auth'])
	->name('lodges::my.')
	->prefix('my/lodges')
	->group(function() {
		Route::post('/{lodge}/join', [LodgeMembershipController::class, 'store'])
			->name('join.store')
			->can('join', 'lodge');
		Route::delete('/{lodge}/leave', [LodgeMembershipController::class, 'destroy'])
			->name('leave.destroy')
			->can('leave', 'lodge');
	});
