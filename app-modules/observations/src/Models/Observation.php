<?php

namespace StarWatch\Observations\Models;

use App\Model;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use StarWatch\Lodges\Models\Lodge;

class Observation extends Model
{
	use SoftDeletes;
	
	protected function casts(): array
	{
		return [
			'observed_at' => 'datetime',
		];
	}
	
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
	
	public function lodge(): BelongsTo
	{
		return $this->belongsTo(Lodge::class);
	}
}
