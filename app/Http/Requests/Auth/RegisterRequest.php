<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
	public function authorize(): bool
	{
		return null === $this->user();
	}
	
	public function rules(): array
	{
		return [
			'name' => ['required', 'string', 'max:120'],
			'email' => ['required', 'email', 'max:191', 'unique:users,email'],
			'password' => ['required', 'confirmed', Password::min(8)],
		];
	}
}
