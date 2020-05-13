<?php

namespace NerdWerkApp\Controllers;

use \NerdWerk\Annotations\Route as Route;
use \NerdWerk\Annotations\EventListener as EventListener;

class Home extends \NerdWerk\Controller
{

	/**
	 * @Route(pattern="/")
	 */
	public function index()
	{
		echo "NerdWerk PHP Framework";
	}

	/**
	 * @Route(method="get", pattern="/framework/test/{icles}")
	 */
	public function test($icles)
	{
		echo "In the test method, ".$icles;
	}

	/**
	 * @EventListener(event="framework_booted")
	 */
	public function eventTest($timestamp)
	{
		echo "In eventTest! ".$timestamp;
	}

}