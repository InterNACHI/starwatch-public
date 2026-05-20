@extends('layouts.app')

@section('title', 'New star party')

@section('content')
	<div class="mx-auto max-w-xl">
		<x-page-title>
			New star party
		</x-page-title>
		
		<div class="mt-6 card">
			{{ Aire::open()->route('star-parties::my.party.store', $lodge) }}
			{{ Aire::input('title', 'Title')->required() }}
			{{ Aire::input('scheduled_at', 'Scheduled at')->type('datetime-local')->required() }}
			{{ Aire::input('location', 'Location')->required() }}
			{{ Aire::input('capacity', 'Capacity')->type('number')->required() }}
			{{ Aire::submit('Create')->variant('primary') }}
			{{ Aire::close() }}
		</div>
	</div>
@endsection
