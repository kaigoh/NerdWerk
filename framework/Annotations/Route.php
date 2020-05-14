<?php

namespace NerdWerk\Annotations;

/**
 * @Annotation
 */
final class Route
{

	public $method;
	public $pattern;
	public $authenticationProvider = false;
	public $authenticationPermission = false;

}