<?php

namespace NerdWerkApp\Controllers;

use \NerdWerk\View as View;
use \NerdWerk\Template as Template;
use \NerdWerk\Http\Response as Response;
use \NerdWerk\Http\SimpleResponse as SimpleResponse;
use \NerdWerk\Annotations\Route as Route;
use \NerdWerk\Annotations\EventListener as EventListener;

class Home extends \NerdWerk\Controller
{

	/**
	 * @Route(pattern="/")
	 */
	public function index(&$framework)
	{
		return View::renderToResponse(200, "home");
	}

	/**
	 * @Route(method="get", pattern="/hello", authenticationRequired=true)
	 */
	public function hello(&$framework)
	{
		return Template::renderToResponse(200, "hello", (array)$framework->getUser());
	}

	/**
	 * @Route(method="get", pattern="/hello.json", authenticationRequired=true)
	 */
	public function helloJson(&$framework)
	{
		return new Response(200, ["hi" => $framework->getUser()->username], "application/json");
	}

	/**
	 * @Route(method="get", pattern="/hello.xml", authenticationRequired=true)
	 */
	public function helloXml(&$framework)
	{
		return new Response(200, ["hi" => $framework->getUser()->username], "text/xml");
	}

	/**
	 * @EventListener(event="framework_booted")
	 */
	public function eventTest($timestamp)
	{
		//echo "In eventTest! ".$timestamp;
	}

}