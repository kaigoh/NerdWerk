<?php

namespace NerdWerkApp\Controllers;

use NerdWerk\Annotations\Route as Route;

class Home
{

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

}