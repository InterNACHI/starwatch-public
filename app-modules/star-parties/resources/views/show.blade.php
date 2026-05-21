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
			
			<p class="mt-1 text-xs uppercase tracking-wide text-slate-400">
				Waitlist: {{ $party->getWaitlistCount() }}
			</p>

			<div class="mt-6">
				@auth
					@if($user_rsvp)
						<p class="mb-3 text-sm font-medium text-emerald-700">
							You're {{ $user_rsvp->status->value }} for this event.
						</p>
						<form action="{{ route('star-parties::my.party.rsvp.destroy', [$party->lodge, $party, $user_rsvp]) }}" method="POST">
							@csrf
							@method('DELETE')
							<button type="submit" class="btn btn-secondary">
								Cancel RSVP
							</button>
						</form>
					@elseif($user_waitlist_entry && $user_waitlist_entry->status->value === 'waiting')
						<p class="mb-3 text-sm font-medium text-amber-700">
							You're #{{ $waitlist_service->getPosition($user_waitlist_entry) }} on the waitlist.
						</p>
						<form action="{{ route('star-parties::my.party.waitlist.destroy', [$party->lodge, $party, $user_waitlist_entry]) }}" method="POST">
							@csrf
							@method('DELETE')
							<button type="submit" class="btn btn-secondary">
								Leave the waitlist
							</button>
						</form>
					@elseif($party->isFull())
						<form action="{{ route('star-parties::my.party.waitlist.store', [$party->lodge, $party]) }}" method="POST">
							@csrf
							<button type="submit" class="btn btn-primary">
								Join the waitlist
							</button>
						</form>
					@else
						<form action="{{ route('star-parties::my.party.rsvp.store', [$party->lodge, $party]) }}" method="POST">
							@csrf
							<button type="submit" class="btn btn-primary">
								RSVP
							</button>
						</form>
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
