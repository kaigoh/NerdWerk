<?php

namespace NerdWerk\Authentication;

class Directory
{

    private $directory = [];

    public function __construct()
    {

    }

    public function domain($domain)
    {
        return $this->domainExists($domain);
    }

    public function createDomain($domain, $name)
    {
        if(!$this->domainExists($domain))
        {
            $this->directory[$domain] = new Domain($domain, $name);
        } else {
            $this->directory[$domain]->name = $name;
        }
        return $this->directory[$domain];
    }

    public function domainExists($domain)
    {
        if(isset($this->directory[$domain]))
        {
            return $this->directory[$domain];
        }
        return false;
    }

}