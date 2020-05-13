<?php

namespace NerdWerk;

class Framework
{

    public $config;
    public $events;
    public $router;
    public $input;

    public function __construct()
    {
        // Load application configuration...
        $this->config = new \NerdWerk\Config();

        // Initialise the events engine...
        $this->events = new \NerdWerk\Events($this->config);

        // Transmit the framework_booting event...
        $this->events->emit("framework_booting", [date("c")]);

        // Gather inputs
        $this->input = new \NerdWerk\Input($this->config);

        // Initialise the routing engine...
        $this->router = new \NerdWerk\Router($this->config);

    }

    public function start()
    {
        // Transmit the framework_booted event...
        $this->events->emit("framework_booted", [date("c")]);

        // Start the router
        $this->router->route();

    }

}