<?php

use App\User;
use Illuminate\Support\Facades\Auth;

if (! function_exists('user')) {
	function user(): ?User
	{
		return Auth::user();
	}
}
