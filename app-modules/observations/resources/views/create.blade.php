@extends('layouts.app')

@section('title', 'Log observation')

@section('content')
	<div class="mx-auto max-w-xl">
		<x-page-title>
			Log observation
		</x-page-title>
		
		<div class="mt-6 card">
			{{ Aire::open()->route('observations::my.observation.store') }}
			{{ Aire::input('target', 'Target')->required()->helpText('e.g. "M31", "Jupiter", "Comet C/2023 A3"') }}
			{{ Aire::dateTimeLocal('observed_at', 'Observed at')->required() }}
			{{ Aire::textarea('notes', 'Notes')->autoSize() }}
			{{ Aire::select($lodges, 'lodge_id', 'Companion lodge')->prependEmptyOption('No companion lodge') }}
			{{ Aire::submit('Save observation')->variant('primary') }}
			{{ Aire::close() }}
		</div>
	</div>
@endsection
