<?php

namespace StarWatch\StarParties\Models;

use App\Model;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use StarWatch\Lodges\Models\Lodge;
use StarWatch\StarParties\Enums\RsvpStatus;

class StarParty extends Model
{
	use SoftDeletes;
	
	protected $table = 'star_parties';
	
	protected function casts(): array
	{
		return [
			'scheduled_at' => 'datetime',
			'capacity' => 'integer',
		];
	}
	
	public function lodge(): BelongsTo
	{
		return $this->belongsTo(Lodge::class);
	}
	
	public function rsvps(): HasMany
	{
		return $this->hasMany(StarPartyRsvp::class);
	}
	
	public function confirmed_rsvps(): HasMany
	{
		return $this->rsvps()->where('status', RsvpStatus::Confirmed);
	}
	
	public function isFull(): bool
	{
		return $this->confirmed_rsvps()->count() >= $this->capacity;
	}
	
	public function hasRsvpFrom(User|int $user_id): bool
	{
		if ($user_id instanceof User) {
			$user_id = $user_id->getKey();
		}
		
		return $this->rsvps()->where('user_id', $user_id)->exists();
	}
}
