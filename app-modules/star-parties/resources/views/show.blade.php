@extends('layouts.app')

@section('title', $party->title)

@section('content')
	<x-page-title>
		{{ $party->title }}
		<x-slot:subtitle>
			{{ $party->scheduled_at->format('l, F j, Y · g:ia') }} @ {{ $party->location }}
		</x-slot:subtitle>
		<x-slot:action>
			@can('update', $party)
				<a href="{{ route('star-parties::my.party.edit', [$party->lodge, $party]) }}" class="btn btn-secondary">
					Make changes
				</a>
			@endcan
		</x-slot:action>
	</x-page-title>
	
	<div class="grid gap-8 lg:grid-cols-3">
		<div class="lg:col-span-2 card">
			<h2 class="text-lg font-semibold">Attendance</h2>
			@php($remaining = max(0, $party->capacity - $confirmed_count))
			<p class="mt-2 text-sm text-slate-600">
				<span class="font-medium">{{ $confirmed_count }}</span> / {{ $party->capacity }} confirmed
				@if($remaining > 0)
					· {{ $remaining }} spot{{ $remaining === 1 ? '' : 's' }} left
				@else
					· <span class="font-semibold text-amber-600">At capacity</span>
				@endif
			</p>
			
			<div class="mt-6">
				@auth
					@if($user_rsvp)
						<p class="mb-3 text-sm font-medium text-emerald-700">
							You're {{ $user_rsvp->status->value }} for this event.
						</p>
						{{ Aire::open()->route('star-parties::my.party.rsvp.destroy', [$party->lodge, $party, $user_rsvp])->method('DELETE') }}
						{{ Aire::submit('Cancel RSVP')->class('btn-secondary') }}
						{{ Aire::close() }}
					@else
						{{ Aire::open()->route('star-parties::my.party.rsvp.store', [$party->lodge, $party])->post() }}
						{{ Aire::submit($party->isFull() ? 'Event full' : 'RSVP')
							->class($party->isFull() ? 'btn-secondary opacity-60' : 'btn-primary')
							->disabled($party->isFull()) }}
						{{ Aire::close() }}
					@endif
				@else
					<a href="{{ route('login') }}" class="btn-primary">Log in to RSVP</a>
				@endauth
			</div>
		</div>
		
		<div class="card">
			<h2 class="text-lg font-semibold">Hosted by</h2>
			<p class="mt-2 text-slate-600">
				<a href="{{ route('lodges::frontend.show', $party->lodge) }}" class="text-indigo-600 hover:underline">
					{{ $party->lodge->name }}
				</a>
				<br>
				<span class="text-sm text-slate-500">{{ $party->lodge->city }}, {{ $party->lodge->region }}</span>
			</p>
		</div>
	</div>
@endsection
