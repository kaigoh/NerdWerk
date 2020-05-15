<?php

namespace NerdWerk\Annotations;

/**
 * @Annotation
 */
final class EventListener
{

	public $event;
	public $callback;

	public static function fromArray($input = []) : \NerdWerk\Annotations\EventListener
	{
		if(is_array($input))
		{
			$config = [
				"event" => null,
				"callback" => null,
			];

			array_change_key_case($input, CASE_LOWER);

			$merged = array_merge($config, $input);

			$e = new EventListener();

			$e->event = $merged['event'];
			$e->callback = $merged['callback'];

			return $e;
		} else {
			throw new \NerdWerk\Exceptions\NerdWerkEventListenerConfigurationNotValidException("Value passed to fromArray was not an array", 200);
		}
	}

}