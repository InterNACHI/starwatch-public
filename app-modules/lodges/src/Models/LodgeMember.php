<?php

namespace StarWatch\Lodges\Models;

use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class LodgeMember extends Pivot
{
	public $incrementing = true;
	
	protected $table = 'lodge_members';
	
	protected function casts(): array
	{
		return [
			'joined_at' => 'datetime',
		];
	}
	
	public function lodge(): BelongsTo
	{
		return $this->belongsTo(Lodge::class);
	}
	
	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
