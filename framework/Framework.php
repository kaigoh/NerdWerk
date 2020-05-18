<?php

namespace NerdWerk;

/**
 * Framework Constants
 */
 $frameworkConstants = [
    "NW_FRAMEWORK_PATH" => "framework",
    "NW_APPLICATION_PATH" => "application",
    "NW_CONFIG_PATH" => "config",
    "NW_CACHE_PATH" => "cache",
    "NW_LOG_PATH" => "logs",
    "NW_AUTHENTICATION_ALGORITHM" => "sha256",
    "NW_RENDERERS_XML_ROOT_TAG" => "response",
    "NW_ENVIRONMENT" => "not-set",
];
foreach($frameworkConstants as $k => $v)
{
    if(!defined($k))
    {
        define($k, (getenv($k) ? getenv($k) : $v));
    }
    if($k == "NW_ENVIRONMENT")
    {
        if(NW_ENVIRONMENT == "not-set")
        {
            die("NW_ENVIRONMENT has not been defined! Set it to either 'development' or 'production' in the environment file, or by defining a constant in index.php");
        }
    }
}

use \NerdWerk\Authentication\CredentialSource as CredentialSource;
use \NerdWerk\Authentication\AuthenticationProvider as AuthenticationProvider;

class Framework
{

    public $config;
    public $events;
    public $router;
    public $input;
    public $credentialSources = [];
    public $authenticationProviders = [];
    public $request;
    public $response;

    public function __construct()
    {
        
        // Load application configuration
        $this->config = new \NerdWerk\Config();

    }

    public function __destruct()
    {
        if($this->response)
        {
            if(is_a($this->response, "\NerdWerk\Http\Response"))
            {
                $this->response->sendResponse();
            } else {
                die($this->response);
            }
        }
    }

    public function addAuthenticationProvider(AuthenticationProvider $provider)
    {
        $this->authenticationProviders[] = $provider;
    }

    public function addCredentialSource(CredentialSource $source)
    {
        $this->credentialSources[] = $source;
    }

    public function getUser()
    {
        if(count($this->credentialSources) > 0 && count($this->authenticationProviders))
        {
            foreach($this->credentialSources as $s)
            {
                // Try and fetch some credentials from the credential source...
                if(is_a($s, "\NerdWerk\Authentication\CredentialSource") && $c = $s->getCredentials())
                {
                    // If we have some credentials, test them against the authentication providers...
                    foreach($this->authenticationProviders as $a)
                    {
                        if(is_a($a, "\NerdWerk\Authentication\AuthenticationProvider"))
                        {
                            $user = $a->authenticate($c->domain, $c->username, $c->password);
                            if($user)
                            {
                                return $user;
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    public function getInstance()
    {
        return $this;
    }

    public function run()
    {

        // Initialise the events engine
        $this->events = new \NerdWerk\Events($this, $this->config);

        // Gather inputs
        $this->input = new \NerdWerk\Input($this->config);

        // Initialise the routing engine
        $this->router = new \NerdWerk\Router($this, $this->config, $this->authenticationProviders);

        // Transmit the framework_booted event...
        $this->events->emit("framework_booted", [date("c")]);

        // Start the router
        $this->router->route();

    }

    public function end()
    {
        exit();
    }

}