<?php

namespace StarWatch\StarParties\Http\Requests;

use App\Http\Requests\FormRequest;
use StarWatch\StarParties\Models\StarParty;

final class AddToWaitlistRequest extends FormRequest
{
    /**
     * The user must be authorized to join the bound party's
     * waitlist.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $party = $this->route('party');

        if (! $party instanceof StarParty) {
            return false;
        }

        return $this->user()?->can('join', [\StarWatch\StarParties\Models\WaitlistEntry::class, $party]) === true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }
}
