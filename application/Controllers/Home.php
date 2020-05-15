<?php

namespace NerdWerkApp\Controllers;

use \NerdWerk\Http\Response as Response;
use \NerdWerk\Http\SimpleResponse as SimpleResponse;
use \NerdWerk\Annotations\Route as Route;
use \NerdWerk\Annotations\EventListener as EventListener;

class Home extends \NerdWerk\Controller
{

	/**
	 * @Route(pattern="/")
	 */
	public function index($framework)
	{
		return new SimpleResponse(200, "NerdWerk PHP Framework");
	}

	/**
	 * @Route(method="get", pattern="/framework/test/{icles}", authenticationRequired=true)
	 */
	public function test($icles, $framework)
	{
		return new Response(200, ["user" => $framework->getUser()->username], "application/json");
	}

	/**
	 * @EventListener(event="framework_booted")
	 */
	public function eventTest($timestamp)
	{
		//echo "In eventTest! ".$timestamp;
	}

}