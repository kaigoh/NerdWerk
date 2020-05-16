<?php

namespace NerdWerk\Annotations;

/**
 * @Annotation
 */
final class Route
{

	public $method;
	public $pattern;
	public $callback;
	public $authenticationRequired = false;
	public $authenticationPermission = false;

	public static function fromArray($input = []) : \NerdWerk\Annotations\Route
	{
		if(is_array($input))
		{
			$config = [
				"method" => null,
				"pattern" => null,
				"callback" => null,
				"authenticationrequired" => null,
				"authenticationpermission" => null,
			];

			array_change_key_case($input, CASE_LOWER);

			$merged = array_merge($config, $input);

			$r = new Route();

			$r->method = $merged['method'];
			$r->pattern = $merged['pattern'];
			$r->callback = $merged['callback'];
			$r->authenticationProvider = $merged['authenticationrequired'];
			$r->authenticationPermission = $merged['authenticationpermission'];

			return $r;
		} else {
			throw new \NerdWerk\Exceptions\RouteConfigurationNotValidException("Value passed to fromArray was not an array", 200);
		}
	}

}