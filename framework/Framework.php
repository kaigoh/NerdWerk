<?php

namespace NerdWerk;

use \NerdWerk\Authentication\AuthenticationProvider as AuthenticationProvider;

class Framework
{

    public $config;
    public $events;
    public $router;
    public $input;
    public $user;
    public $authenticationProviders = [];

    public function __construct()
    {
        
        // Load application configuration
        $this->config = new \NerdWerk\Config();

        // Initialise the events engine
        $this->events = new \NerdWerk\Events($this->config);

        // Transmit the framework_booting event
        $this->events->emit("framework_booting", [date("c")]);

        // Gather inputs
        $this->input = new \NerdWerk\Input($this->config);

    }

    public function addAuthenticationProvider(string $name, AuthenticationProvider $provider)
    {
        $this->authenticationProviders[$name] = $provider;
    }

    public function start()
    {

        // Authenticate the user...
        //$this->user = $this->authenticationProviders["config_file"]->authenticate("root", "admin", "admin");

        // Initialise the routing engine
        $this->router = new \NerdWerk\Router($this->config, $this->authenticationProviders, $this->user);

        // Transmit the framework_booted event...
        $this->events->emit("framework_booted", [date("c")]);

        // Start the router
        $this->router->route();

    }

}