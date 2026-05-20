@props([
	'subtitle' => new Illuminate\View\ComponentSlot(),
	'blurb' => new Illuminate\View\ComponentSlot(),
	'action' => new Illuminate\View\ComponentSlot(),
])

<div {{ $attributes->merge(['class' => 'mb-6 w-full flex items-end justify-between']) }}>
	<div>
		<h1 class="text-2xl font-bold">
			{{ $slot }}
		</h1>
		
		@if($subtitle->isNotEmpty())
			<p class="text-sm text-slate-500">
				{{ $subtitle }}
			</p>
		@endif
	</div>
	
	@if($action->isNotEmpty())
		<div>
			{{ $action }}
		</div>
	@endif
</div>
