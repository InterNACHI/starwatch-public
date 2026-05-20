<?php

namespace StarWatch\StarParties\Models;

use App\Model;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use StarWatch\StarParties\Enums\RsvpStatus;

class StarPartyRsvp extends Model
{
	protected $table = 'star_party_rsvps';
	
	protected function casts(): array
	{
		return [
			'status' => RsvpStatus::class,
		];
	}
	
	public function star_party(): BelongsTo
	{
		return $this->belongsTo(StarParty::class);
	}
	
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
