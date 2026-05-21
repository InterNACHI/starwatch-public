<?php

namespace StarWatch\StarParties\Models;

use App\Model;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WaitlistEntry extends Model
{
    protected $table = 'waitlist_entries';

    protected $guarded = [];

    /**
     * The star party this waitlist entry is queued against.
     *
     * @return BelongsTo
     */
    public function starParty(): BelongsTo
    {
        return $this->belongsTo(StarParty::class);
    }

    /**
     * The user waiting on a seat.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate this entry's 1-based position in the star party's
     * waitlist queue. Ordering is determined by primary key, which
     * reflects insertion order.
     *
     * @return int
     */
    public function position(): int
    {
        return static::query()
            ->where('star_party_id', $this->star_party_id)
            ->where('id', '<', $this->id)
            ->count() + 1;
    }
}
