<?php

use Illuminate\Support\Facades\Route;
use StarWatch\Observations\Http\Controllers\ObservationController;
use StarWatch\Observations\Models\Observation;

Route::middleware(['web', 'auth'])
	->name('observations::my.observation.')
	->prefix('my/observations')
	->group(function() {
		Route::get('/', [ObservationController::class, 'index'])
			->name('index')
			->breadcrumb('My observations', 'home');
		Route::get('/create', [ObservationController::class, 'create'])
			->name('create')
			->can('create', Observation::class)
			->breadcrumb('New observation', '.index');
		Route::post('/', [ObservationController::class, 'store'])
			->name('store')
			->can('create', Observation::class);
		Route::get('/{observation}/edit', [ObservationController::class, 'edit'])
			->name('edit')
			->can('update', 'observation')
			->breadcrumb(fn(Observation $observation) => 'Edit '.$observation->target, '.index');
		Route::put('/{observation}', [ObservationController::class, 'update'])
			->name('update')
			->can('update', 'observation');
		Route::delete('/{observation}', [ObservationController::class, 'destroy'])
			->name('destroy')
			->can('delete', 'observation');
	});
