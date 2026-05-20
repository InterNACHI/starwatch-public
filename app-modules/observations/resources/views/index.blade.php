@extends('layouts.app')

@section('title', 'My observations')

@section('content')
	
	<x-page-title>
		My observations
		<x-slot:subtitle>
			Your sky log, in reverse chronological order.
		</x-slot:subtitle>
		<x-slot:action>
			<a href="{{ route('observations::my.observation.create') }}" class="btn-primary">Log new observation</a>
		</x-slot:action>
	</x-page-title>
	
	<div class="card overflow-hidden p-0">
		<table class="min-w-full divide-y divide-slate-200 text-sm">
			<thead class="bg-slate-50">
				<tr>
					<th class="px-4 py-3 text-left font-medium text-slate-500">
						Target
					</th>
					<th class="px-4 py-3 text-left font-medium text-slate-500">
						Observed
					</th>
					<th class="px-4 py-3 text-left font-medium text-slate-500">
						Lodge
					</th>
					<th class="px-4 py-3"></th>
				</tr>
			</thead>
			<tbody class="divide-y divide-slate-100">
				@forelse ($observations as $observation)
					<tr>
						<td class="px-4 py-3 font-medium text-slate-800">
							{{ $observation->target }}
						</td>
						<td class="px-4 py-3 text-slate-600">
							{{ $observation->observed_at->format('M j, Y @ g:ia') }}
						</td>
						<td class="px-4 py-3 text-slate-600">
							{{ $observation->lodge?->name ?? '—' }}
						</td>
						<td class="px-4 py-3 text-right">
							<a href="{{ route('observations::my.observation.edit', $observation) }}" class="text-indigo-600 hover:underline">
								Edit
							</a>
							
							{{ Aire::route('observations::my.observation.destroy', $observation)
								->class('ml-3 inline')
								->setAttribute('onsubmit', "return confirm('Are you sure?');") }}
							<button type="submit" class="text-slate-400 hover:underline">Delete</button>
							{{ Aire::close() }}
						</td>
					</tr>
				@empty
					<tr>
						<td colspan="4" class="px-4 py-6 text-center text-slate-500">
							No observations yet.
							<a href="{{ route('observations::my.observation.create') }}" class="text-indigo-600 hover:underline">Go log one!</a>
						</td>
					</tr>
				@endforelse
			</tbody>
		</table>
	</div>
	
	<div class="mt-8">
		{{ $observations->links() }}
	</div>
@endsection
