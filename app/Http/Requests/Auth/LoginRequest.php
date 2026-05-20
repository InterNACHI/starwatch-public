<?php

namespace App\Http\Requests\Auth;

use App\Http\Requests\FormRequest;

class LoginRequest extends FormRequest
{
	public function authorize(): bool
	{
		return null === $this->user();
	}
	
	public function rules(): array
	{
		return [
			'email' => ['required', 'email'],
			'password' => ['required', 'string'],
			'remember' => ['nullable', 'boolean'],
		];
	}
}
