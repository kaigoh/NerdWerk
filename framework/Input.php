<?php

namespace NerdWerk;

class Input
{

    private $data = [
        "get" => [],
        "post" => [],
        "put" => null,
        "request" => [],
        "server" => [],
    ];

    public function __construct(\NerdWerk\Config $config = null)
    {

        // Throw an exception if config not passed
        if(!$config)
        {
            throw new \NerdWerk\Exceptions\ConfigException("Application configuration not passed to constructor", 100);
        }

        // Populate the data arrays...
        if($put = file_get_contents("php://input"))
        {
            $this->data['put'] = $put;
        }

        $this->data['get'] = $_GET;

        $this->data['post'] = $_POST;

        $this->data['request'] = $_REQUEST;

        $this->data['server'] = $_SERVER;

    }

    private function input($type = false, $key = false)
    {
        if($type)
        {

            if($type == "put")
            {
                return ($this->data['put'] ? $this->data['put'] : null);
            }

            if($key && isset($this->data[$type]) && isset($this->data[$type][$key]))
            {
                return $this->data[$type][$key];
            }
        }
        return null;
    }

    public function get($key)
    {
        return $this->input("get", $key);
    }

    public function post($key)
    {
        return $this->input("post", $key);
    }

    public function put()
    {
        return $this->input("put");
    }

    public function request($key)
    {
        return $this->input("request", $key);
    }

    public function server($key)
    {
        return $this->input("server", $key);
    }

    public function json($toArray = false)
    {
        if($p = $this->put() && $j = json_decode($p, $toArray))
        {
            return $j;
        }
        return null;
    }

    public function getPost($key)
    {
        if($g = $this->get($key))
        {
            return $g;
        }

        if($p = $this->post($key))
        {
            return $p;
        }

        return null;
    }

    public function postGet($key)
    {
        if($p = $this->post($key))
        {
            return $p;
        }

        if($g = $this->get($key))
        {
            return $g;
        }

        return null;
    }

}