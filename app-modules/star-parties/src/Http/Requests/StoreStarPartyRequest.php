<?php

namespace StarWatch\StarParties\Http\Requests;

use App\Http\Requests\FormRequest;

class StoreStarPartyRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'title' => ['required', 'string', 'max:120'],
			'scheduled_at' => ['required', 'date', 'after:now'],
			'location' => ['required', 'string', 'max:191'],
			'capacity' => ['required', 'integer', 'min:1', 'max:500'],
		];
	}
}
