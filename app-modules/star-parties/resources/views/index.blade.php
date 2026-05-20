@extends('layouts.app')

@section('title', 'Upcoming star parties — '.$lodge->name)

@section('content')
	
	<x-page-title>
		Upcoming star parties
		
		<x-slot:action>
			@can('create', [StarWatch\StarParties\Models\StarParty::class, $lodge])
				<a href="{{ route('star-parties::my.party.create', $lodge) }}" class="btn btn-primary">
					Create a new party
				</a>
			@endcan
		</x-slot:action>
	</x-page-title>
	
	<div class="grid gap-6 md:grid-cols-2">
		@forelse ($parties as $party)
			<a
				href="{{ route('star-parties::frontend.party.show', [$party->lodge, $party]) }}"
				class="card transition hover:shadow-lg hover:ring-blue-200"
			>
				<h2 class="text-lg font-semibold">{{ $party->title }}</h2>
				<p class="mt-1 text-sm text-slate-500">{{ $party->scheduled_at->format('M j, Y · g:ia') }}</p>
				<p class="mt-2 text-sm text-slate-600">{{ $party->location }}</p>
				<p class="mt-3 text-xs uppercase tracking-wide text-slate-400">
					{{ $party->confirmed_count }}/{{ $party->capacity }} confirmed
				</p>
			</a>
		@empty
			<p class="text-slate-500">Nothing scheduled yet.</p>
		@endforelse
	</div>
	
	<div class="mt-8">
		{{ $parties->links() }}
	</div>
@endsection
