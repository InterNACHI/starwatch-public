@extends('layouts.app')

@section('title', $lodge->name)

@section('content')
	<x-page-title class="mb-0">
		{{ $lodge->name }}
		
		<x-slot:subtitle>
			{{ $lodge->city }}, {{ $lodge->region }}
		</x-slot:subtitle>
		
		<x-slot:action>
			@auth
				@if($is_member)
					{{ Aire::open()->route('lodges::my.leave.destroy', $lodge) }}
					{{ Aire::submit('Leave this lodge')->variant('secondary') }}
					{{ Aire::close() }}
				@else
					{{ Aire::open()->route('lodges::my.join.store', $lodge) }}
					{{ Aire::submit('Join this lodge')->variant('primary') }}
					{{ Aire::close() }}
				@endif
			@else
				<a href="{{ route('login') }}" class="btn-primary">
					Log in to join
				</a>
			@endauth
		</x-slot:action>
	</x-page-title>
	
	@if($lodge->blurb)
		<p class="mt-2 mb-6 max-w-3xl text-slate-700">
			{{ $lodge->blurb }}
		</p>
	@endif
	
	<div class="grid gap-8 lg:grid-cols-2">
		
		{{-- Members --}}
		<section class="card">
			<h2 class="text-lg font-semibold">
				Members ({{ $lodge->members->count() }})
			</h2>
			<ul class="mt-4 divide-y divide-slate-100 text-sm">
				@forelse ($lodge->members as $member)
					<li class="py-2">
						{{ $member->name }}
					</li>
				@empty
					<li class="py-2 text-slate-500">
						No members yet.
					</li>
				@endforelse
			</ul>
		</section>
		
		{{-- Star Parties --}}
		<section class="card">
			<h2 class="text-lg font-semibold">
				Upcoming star parties
			</h2>
			
			<ul class="mt-3 -mb-3 text-sm">
				@forelse ($upcoming_parties as $party)
					<li class="-mx-3">
						<a href="{{ route('star-parties::frontend.party.show', [$party->lodge, $party]) }}" class="block rounded p-3 hover:bg-slate-50">
							<p class="font-medium">
								{{ $party->title }}
							</p>
							<p class="text-slate-500">
								{{ $party->scheduled_at->format('M j, Y · g:ia') }} —
								{{ $party->confirmed_count }}/{{ $party->capacity }} confirmed
							</p>
						</a>
					</li>
				@empty
					<li class="text-slate-500">
						No upcoming events.
					</li>
				@endforelse
			</ul>
			
			<div class="mt-9 flex gap-2">
				@can('create', [StarWatch\StarParties\Models\StarParty::class, $lodge])
					<a href="{{ route('star-parties::my.party.create', $lodge) }}" class="btn btn-primary">
						Create a new party
					</a>
				@endcan
				
				<a href="{{ route('star-parties::frontend.party.index', $lodge) }}" class="btn btn-secondary">
					All parties
				</a>
			</div>
		</section>
	</div>
@endsection
