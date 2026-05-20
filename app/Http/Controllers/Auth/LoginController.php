<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
	public function create()
	{
		return view('auth.login');
	}
	
	public function store(LoginRequest $request)
	{
		$credentials = $request->only('email', 'password');
		
		if (Auth::attempt($credentials, $request->boolean('remember'))) {
			$request->session()->regenerate();
			return redirect()->intended(route('home'));
		}
		
		return back()
			->withInput($request->only('email'))
			->withErrors(['email' => 'These credentials do not match our records.']);
	}
	
	public function destroy(Request $request)
	{
		Auth::logout();
		
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		
		return to_route('home');
	}
}
