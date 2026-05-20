<?php

namespace App\Support;

use App\Model;
use Illuminate\Support\Facades\Gate;
use RuntimeException;

/**
 * @template M of Model
 * @mixin \App\Http\Requests\FormRequest
 */
trait HasRouteBoundModel
{
	/**
	 * @param string $parameter
	 * @param class-string<M> $model_class
	 * @param bool $allow_null
	 * @return M|null
	 */
	protected function getRouteBoundModel(string $parameter, string $model_class, bool $allow_null = false): ?Model
	{
		$bound = $this->route($parameter);
		
		if (null === $bound && $allow_null) {
			return null;
		}
		
		if (is_a($bound, $model_class)) {
			return $bound;
		}
		
		$actual_type = is_object($bound) ? get_class($bound) : gettype($bound);
		
		throw new RuntimeException("Expected '{$parameter}' to be bound to '{$model_class}' but got '{$actual_type}'");
	}
	
	/**
	 * @param string $parameter
	 * @param class-string<M> $model_class
	 * @return M|null
	 */
	protected function getOptionalRouteBoundModel(string $parameter, string $model_class): ?Model
	{
		return $this->getRouteBoundModel($parameter, $model_class, true);
	}
	
	/**
	 * @param string $parameter
	 * @param class-string<M> $model_class
	 * @return bool
	 */
	protected function authorizeRouteBoundModel(string $parameter, string $model_class): bool
	{
		return $this->isMethod('POST')
			? Gate::allows('create', $model_class)
			: Gate::allows('update', $this->getRouteBoundModel($parameter, $model_class));
	}
}
