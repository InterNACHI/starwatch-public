@extends('layouts.app')

@section('title', 'Edit observation')

@section('content')
	<div class="mx-auto max-w-xl">
		<x-page-title>
			Edit observation
		</x-page-title>
		
		<div class="mt-6 card">
			{{ Aire::route('observations::my.observation.update', $observation)->bind($observation) }}
			{{ Aire::input('target', 'Target')->required() }}
			{{ Aire::dateTimeLocal('observed_at', 'Observed at')->required() }}
			{{ Aire::textarea('notes', 'Notes')->autoSize() }}
			{{ Aire::select($lodges, 'lodge_id', 'Companion lodge')->prependEmptyOption('No companion lodge') }}
			{{ Aire::submit('Update observation')->variant('primary') }}
			{{ Aire::close() }}
		</div>
	</div>
@endsection
