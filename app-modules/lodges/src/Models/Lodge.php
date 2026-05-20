<?php

namespace StarWatch\Lodges\Models;

use App\Model;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use StarWatch\StarParties\Models\StarParty;

class Lodge extends Model
{
	use SoftDeletes;
	
	public function members(): BelongsToMany
	{
		return $this->belongsToMany(User::class, 'lodge_members')
			->using(LodgeMember::class)
			->withPivot('joined_at')
			->withTimestamps();
	}
	
	public function memberships(): HasMany
	{
		return $this->hasMany(LodgeMember::class);
	}
	
	public function parties(): HasMany
	{
		return $this->hasMany(StarParty::class);
	}
	
	public function hasMember(?User $user): bool
	{
		return null !== $user
			&& $this->memberships()->where('user_id', $user->getKey())->exists();
	}
}
