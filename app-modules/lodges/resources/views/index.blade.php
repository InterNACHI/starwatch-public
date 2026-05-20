@extends('layouts.app')

@section('title', 'Lodges')

@section('content')
	<x-page-title>
		Lodges
		<x-slot:subtitle>
			Browse the federation's local clubs.
		</x-slot:subtitle>
	</x-page-title>
	
	<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
		@forelse ($lodges as $lodge)
			<a
				href="{{ route('lodges::frontend.show', $lodge) }}"
				class="card transition group hover:shadow-lg hover:ring-blue-200"
			>
				<h2 class="text-lg font-semibold">
					{{ $lodge->name }}
				</h2>
				<p class="mt-1 text-sm text-slate-500">
					{{ $lodge->city }}, {{ $lodge->region }}
				</p>
				@if($lodge->blurb)
					<p class="mt-3 line-clamp-3 text-sm text-slate-600">
						{{ $lodge->blurb }}
					</p>
				@endif
				<p class="mt-4 text-xs uppercase tracking-wide text-slate-400">
					{{ $lodge->memberships_count }} {{ Str::plural('member', $lodge->memberships_count) }}
				</p>
			</a>
		@empty
			<p class="text-slate-500">
				No lodges yet.
			</p>
		@endforelse
	</div>
	
	<div class="mt-8">
		{{ $lodges->links() }}
	</div>
@endsection
