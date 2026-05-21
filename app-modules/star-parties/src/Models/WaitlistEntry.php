<?php

namespace StarWatch\StarParties\Models;

use App\Model;
use App\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WaitlistEntry extends Model
{
    protected $table = 'waitlist_entries';

    protected $guarded = [];

    public function starParty(): BelongsTo
    {
        return $this->belongsTo(StarParty::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The entry's 1-based position in the party's waitlist queue.
     */
    public function position(): int
    {
        return static::query()
            ->where('star_party_id', $this->star_party_id)
            ->where('id', '<', $this->id)
            ->count() + 1;
    }
}
