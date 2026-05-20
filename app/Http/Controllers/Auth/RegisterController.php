<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\User;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
	public function create()
	{
		return view('auth.register');
	}
	
	public function store(RegisterRequest $request)
	{
		$user = User::create([
			'name' => $request->input('name'),
			'email' => $request->input('email'),
			'password' => $request->input('password'),
			'role' => UserRole::Member,
		]);
		
		Auth::login($user);
		
		return to_route('home');
	}
}
