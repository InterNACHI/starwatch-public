<?php

namespace StarWatch\Observations\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\Observations\Http\Requests\StoreObservationRequest;
use StarWatch\Observations\Http\Requests\UpdateObservationRequest;
use StarWatch\Observations\Models\Observation;

class ObservationController extends Controller
{
	public function index(Request $request)
	{
		return view('observations::index', [
			'observations' => $request->user()->observations()
				->with('lodge')
				->orderByDesc('observed_at')
				->paginate(),
		]);
	}
	
	public function create()
	{
		return view('observations::create', [
			'lodges' => Lodge::orderBy('name')->pluck('name', 'id'),
		]);
	}
	
	public function store(StoreObservationRequest $request)
	{
		$request->user()->observations()->create($request->validated());
		
		return to_route('observations::my.observation.index')
			->with('status', 'Observation logged.');
	}
	
	public function edit(Observation $observation)
	{
		return view('observations::edit', [
			'observation' => $observation,
			'lodges' => Lodge::orderBy('name')->pluck('name', 'id'),
		]);
	}
	
	public function update(UpdateObservationRequest $request, Observation $observation)
	{
		$observation->update($request->validated());
		
		return to_route('observations::my.observation.index')
			->with('status', 'Observation updated.');
	}
	
	public function destroy(Observation $observation)
	{
		$observation->delete();
		
		return to_route('observations::my.observation.index')
			->with('status', 'Observation removed.');
	}
}
