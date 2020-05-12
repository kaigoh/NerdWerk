<?php

namespace NerdWerk;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class Router
{
    public $router;

    public function __construct(\NerdWerk\Config $config = null)
    {
        // Initialise the routing engine...
        $this->router = new \Bramus\Router\Router();

        // Populate routes from annotations...
        // ToDo

        // Populate routes from config files...
        foreach($config->routes as $route)
        {
            if(count(array_values($route)) >= 3)
            {
                $this->router->match(strtoupper($route[0]), $route[1], $route[2]);
            } else {
                throw new \NerdWerk\Exceptions\NerdWerkRouteConfigurationNotValidException("Route configuration expects at least three parameters: method, pattern and function / callable", 201);
            }
        }

        // Run the configured routes
        $this->router->run();
    }
}