<?php

namespace StarWatch\StarParties\Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use StarWatch\StarParties\Models\StarParty;
use StarWatch\StarParties\Models\WaitlistEntry;

class WaitlistEntryFactory extends Factory
{
    protected $model = WaitlistEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'star_party_id' => StarParty::factory(),
            'user_id' => User::factory(),
        ];
    }
}
