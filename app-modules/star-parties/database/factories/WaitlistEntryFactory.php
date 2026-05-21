<?php

namespace StarWatch\StarParties\Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use StarWatch\StarParties\Enums\WaitlistEntryStatus;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\WaitlistEntry;

final class WaitlistEntryFactory extends Factory
{
    protected $model = WaitlistEntry::class;

    /**
     * Default attributes for a newly-built waitlist entry.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'star_party_id' => StarParty::factory(),
            'user_id' => User::factory(),
            'position' => 1,
            'status' => WaitlistEntryStatus::Waiting,
            'joined_at' => now(),
        ];
    }
}
