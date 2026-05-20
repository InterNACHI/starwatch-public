<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])
	->name('home')
	->breadcrumb('Home');

Route::middleware('guest')->group(function() {
	Route::get('/login', [LoginController::class, 'create'])
		->name('login')
		->breadcrumb('Log in', 'home');
	Route::post('/login', [LoginController::class, 'store'])->name('login.store');
	Route::get('/register', [RegisterController::class, 'create'])
		->name('register')
		->breadcrumb('Sign up', 'home');
	Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::post('/logout', [LoginController::class, 'destroy'])
	->middleware('auth')
	->name('logout');
