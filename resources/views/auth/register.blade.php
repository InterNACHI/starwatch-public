@extends('layouts.app')

@section('title', 'Register')

@section('content')
	<div class="mx-auto max-w-md">
		<div class="card">
			
			<x-page-title>
				Create an account
				<x-slot:subtitle>
					Join the federation in under a minute.
				</x-slot:subtitle>
			</x-page-title>
			
			<div class="mt-6">
				{{ Aire::route('register.store') }}
				{{ Aire::input('name', 'Name')->required() }}
				{{ Aire::email('email', 'Email')->required() }}
				{{ Aire::password('password', 'Password')->required() }}
				{{ Aire::password('password_confirmation', 'Confirm password')->required() }}
				{{ Aire::submit('Create account')->class('w-full')->variant('primary') }}
				{{ Aire::close() }}
			</div>
			
			<p class="mt-9 pt-6 border-t border-gray-50 text-sm text-slate-500">
				Already have an account?
				<a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Log in</a>
			</p>
		
		</div>
	</div>
@endsection
