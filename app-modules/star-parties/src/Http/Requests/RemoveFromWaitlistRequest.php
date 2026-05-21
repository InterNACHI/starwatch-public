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

        if ($party !== null && $entry->star_party_id !== $party->getKey()) {
            return false;
        }

        return $this->user()?->can('leave', $entry) === true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
