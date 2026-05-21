<?php

namespace StarWatch\StarParties\Models;

use App\Model;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use StarWatch\StarParties\Enums\WaitlistEntryStatus;

final class WaitlistEntry extends Model
{
    protected $table = 'waitlist_entries';

    protected function casts(): array
    {
        return [
            'status' => WaitlistEntryStatus::class,
            'joined_at' => 'datetime',
            'position' => 'integer',
        ];
    }

    /**
     * The star party this entry is waiting on.
     *
     * @return BelongsTo
     */
    public function starParty(): BelongsTo
    {
        return $this->belongsTo(StarParty::class);
    }

    /**
     * The user waiting in the queue.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
