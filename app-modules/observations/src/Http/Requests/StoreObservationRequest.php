<?php

namespace StarWatch\Observations\Http\Requests;

use App\Http\Requests\FormRequest;

class StoreObservationRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'target' => ['required', 'string', 'max:191'],
			'observed_at' => ['required', 'date'],
			'notes' => ['nullable', 'string', 'max:2000'],
			'lodge_id' => ['nullable', 'integer', 'exists:lodges,id'],
		];
	}
}
