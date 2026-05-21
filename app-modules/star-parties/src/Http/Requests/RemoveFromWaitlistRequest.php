<?php

namespace StarWatch\StarParties\Http\Requests;

use App\Http\Requests\FormRequest;
use StarWatch\StarParties\Models\WaitlistEntry;

final class RemoveFromWaitlistRequest extends FormRequest
{
    /**
     * The user must be authorized to leave their own waitlist
     * entry.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $entry = $this->route('entry');
        $party = $this->route('party');

        if (! $entry instanceof WaitlistEntry) {
            return false;
        }

        if ($party !== null && $entry->star_party_id !== $party->id) {
            return false;
        }

        return $this->user()?->can('leave', $entry) === true;
    }

    /**
     * Leaving the waitlist takes no input beyond the route's bound
     * models, so this rule set is intentionally empty.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
