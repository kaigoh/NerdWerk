<?php

namespace NerdWerk;

class Bootstrap
{

    public $config;
    public $router;

    public function __construct()
    {
        // Load application configuration...
        $this->config = new \NerdWerk\Config();

        // Initialise the routing engine...
        $this->router = new \NerdWerk\Router($this->config);
    }

}