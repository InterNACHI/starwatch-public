<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<title>
		@yield('title', 'StarWatch Collective')
	</title>
	<link href="{{ mix('css/app.css') }}" rel="stylesheet" />
</head>
<body class="h-full flex flex-col text-slate-800 antialiased">

{{-- Top Navigation --}}
<nav class="bg-slate-900 text-slate-100">
	<div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4 text-white/70">
		<a href="{{ route('home') }}" class="text-lg font-semibold tracking-wide">
			StarWatch Collective
		</a>
		<div class="flex items-center gap-x-6 text-sm">
			<a href="{{ route('lodges::frontend.index') }}" class="font-medium hover:text-white/100">
				Lodges
			</a>
			@auth
				<a href="{{ route('observations::my.observation.index') }}" class="font-medium hover:text-white/100">
					My observations
				</a>
			@endauth
			@auth
				|
				<span class="hidden sm:inline">
					{{ user()->name }}
				</span>
				{{ Aire::route('logout')->class('inline') }}
				<button type="submit" class="font-medium hover:text-white/100">
					Log out
				</button>
				{{ Aire::close() }}
			@else
				<a href="{{ route('login') }}" class="font-medium hover:text-white/100">
					Log in
				</a>
				<a href="{{ route('register') }}" class="rounded bg-indigo-500 px-3 py-1.5 text-white hover:bg-indigo-400">
					Sign up
				</a>
			@endauth
		</div>
	</div>
</nav>

{{-- Main Section --}}
<main class="mx-auto w-full max-w-6xl px-6 py-10">
	
	@unless(Route::is('home'))
		<div class="mb-6 -mt-6">
			<x-breadcrumbs framework="tailwind" />
		</div>
	@endunless
	
	@if(session('status'))
		<div class="mb-6 rounded-md bg-emerald-50 px-4 py-3 text-sm text-emerald-700 ring-1 ring-emerald-200">
			{{ session('status') }}
		</div>
	@endif
	
	@if(session('error'))
		<div class="mb-6 rounded-md bg-red-50 px-4 py-3 text-sm text-red-700 ring-1 ring-red-200">
			{{ session('error') }}
		</div>
	@endif
	
	@yield('content')

</main>

<footer class="mt-auto bg-slate-100 text-slate-400 border-t border-slate-200">
	<div class="mx-auto max-w-6xl py-6">
		&copy; {{ now()->year }} StarWatch Collective
	</div>
</footer>

<script src="{{ mix('js/app.js') }}" defer></script>

</body>
</html>
