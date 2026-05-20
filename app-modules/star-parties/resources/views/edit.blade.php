@extends('layouts.app')

@section('title', 'Edit star party')

@section('content')
	<div class="mx-auto max-w-xl">
		<x-page-title>
			Edit star party
		</x-page-title>
		
		<div class="mt-6 card">
			{{ Aire::open()->route('star-parties::my.party.update', [$party->lodge, $party]) }}
			{{ Aire::input('title', 'Title')->value($party->title)->required() }}
			{{ Aire::input('scheduled_at', 'Scheduled at')->type('datetime-local')->value($party->scheduled_at->format('Y-m-d\TH:i'))->required() }}
			{{ Aire::input('location', 'Location')->value($party->location)->required() }}
			{{ Aire::input('capacity', 'Capacity')->type('number')->value($party->capacity)->required() }}
			{{ Aire::submit('Update')->variant('primary') }}
			{{ Aire::close() }}
		</div>
	</div>
@endsection
