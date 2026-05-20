@extends('layouts.app')

@section('title', 'Log in')

@section('content')
	<div class="mx-auto max-w-md">
		<div class="card">
			
			<x-page-title>
				Log in
				<x-slot:subtitle>
					Welcome back, observer.
				</x-slot:subtitle>
			</x-page-title>
			
			<div class="mt-6">
				{{ Aire::route('login.store') }}
				{{ Aire::email('email', 'Email')->required() }}
				{{ Aire::password('password', 'Password')->required() }}
				{{ Aire::checkbox('remember', 'Remember me') }}
				{{ Aire::submit('Log in')->class('w-full')->variant('primary') }}
				{{ Aire::close() }}
			</div>
			
			<p class="mt-9 pt-6 border-t border-gray-50 text-sm text-slate-500">
				Don't have an account?
				<a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Register</a>
			</p>
			
		</div>
	</div>
@endsection
