<?php

namespace App;

use App\Enums\UserRole;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use StarWatch\Observations\Models\Observation;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
	use Authenticatable;
	use Authorizable;
	use CanResetPassword;
	use MustVerifyEmail;
	use HasFactory;
	use Notifiable;
	
	protected $hidden = [
		'password',
		'remember_token',
	];
	
	protected function casts(): array
	{
		return [
			'email_verified_at' => 'datetime',
			'password' => 'hashed',
			'role' => UserRole::class,
		];
	}
	
	public function observations(): HasMany
	{
		return $this->hasMany(Observation::class);
	}
	
	public function isOrganizer(): bool
	{
		return $this->role === UserRole::Organizer || $this->isAdmin();
	}
	
	public function isAdmin(): bool
	{
		return $this->role === UserRole::Admin;
	}
}
