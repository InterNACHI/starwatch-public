@extends('layouts.app')

@section('title', 'Welcome — StarWatch Collective')

@section('content')
	<section class="relative isolate overflow-hidden rounded-2xl bg-slate-950 p-10 text-slate-100 shadow-xl ring-1 ring-white/10 sm:p-14">
		<div
			class="absolute inset-0 -z-10 bg-[radial-gradient(ellipse_at_top_right,theme(colors.indigo.700/40),transparent_55%),radial-gradient(ellipse_at_bottom_left,theme(colors.blue.900/35),transparent_60%)]"
			aria-hidden="true"
		></div>
		
		<svg class="pointer-events-none absolute inset-0 -z-10 size-full opacity-70" aria-hidden="true">
			<defs>
				<pattern id="stars-chart" x="0" y="0" width="120" height="120" patternUnits="userSpaceOnUse">
					<circle cx="15" cy="22" r="0.7" fill="white" opacity="0.7" />
					<circle cx="78" cy="48" r="0.5" fill="white" opacity="0.45" />
					<circle cx="42" cy="92" r="1" fill="white" opacity="0.9" />
					<circle cx="100" cy="18" r="0.6" fill="white" opacity="0.55" />
					<circle cx="58" cy="65" r="0.4" fill="white" opacity="0.35" />
					<circle cx="8" cy="105" r="0.8" fill="white" opacity="0.75" />
					<circle cx="92" cy="86" r="0.5" fill="white" opacity="0.4" />
				</pattern>
			</defs>
			<rect width="100%" height="100%" fill="url(#stars-chart)" />
		</svg>
		
		<div class="grid gap-10 lg:grid-cols-[3fr_2fr] lg:items-center">
			<div>
				<p class="font-mono text-xs uppercase tracking-[0.22em] text-indigo-300">
					41.8&deg; N &middot; 71.4&deg; W &middot; Bortle 4
				</p>
				
				<h1 class="mt-5 max-w-[18ch] text-5xl font-semibold tracking-tight text-balance sm:text-6xl">
					A federation under one sky.
				</h1>
				
				<p class="mt-6 max-w-[55ch] text-lg leading-8 text-slate-300 text-pretty">
					Lodges across the continent, sharing observations, hosting one another at star parties,
					and keeping a careful logbook of the night.
				</p>
				
				<div class="mt-8 flex flex-wrap gap-3">
					<a
						href="{{ route('lodges::frontend.index') }}"
						class="rounded-md bg-indigo-500 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-400"
					>
						Browse lodges
					</a>
					@auth
						<a
							href="{{ route('observations::my.observation.index') }}"
							class="rounded-md bg-white/10 px-4 py-2 text-sm font-semibold text-white ring-1 ring-inset ring-white/20 hover:bg-white/20"
						>
							My observations
						</a>
					@else
						<a
							href="{{ route('register') }}"
							class="rounded-md bg-white/10 px-4 py-2 text-sm font-semibold text-white ring-1 ring-inset ring-white/20 hover:bg-white/20"
						>
							Join the federation
						</a>
					@endauth
				</div>
			</div>
			
			<div class="relative">
				<svg
					viewBox="0 0 280 280"
					class="size-full text-indigo-200"
					fill="none"
					stroke="currentColor"
					stroke-width="0.6"
					stroke-linecap="round"
					aria-hidden="true"
				>
					<g opacity="0.55">
						<path d="M40 70 L95 50 L150 95 L210 60 L245 110" />
						<path d="M95 50 L120 140 L210 60" />
						<path d="M150 95 L165 175 L120 140" />
						<path d="M165 175 L225 200 L245 110" />
						<path d="M165 175 L130 230" />
					</g>
					<g fill="currentColor">
						<circle cx="40" cy="70" r="2.2" />
						<circle cx="95" cy="50" r="3.5" />
						<circle cx="150" cy="95" r="2.4" />
						<circle cx="210" cy="60" r="3" />
						<circle cx="245" cy="110" r="2.6" />
						<circle cx="120" cy="140" r="2" />
						<circle cx="165" cy="175" r="3.2" />
						<circle cx="225" cy="200" r="2.4" />
						<circle cx="130" cy="230" r="2.2" />
					</g>
					<g fill="white" opacity="0.85">
						<circle cx="95" cy="50" r="1.4" />
						<circle cx="210" cy="60" r="1.2" />
						<circle cx="165" cy="175" r="1.3" />
					</g>
				</svg>
			</div>
		</div>
	</section>
@endsection
