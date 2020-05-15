<?php

namespace NerdWerk\Authentication;

class Domain
{

    private $domain;
    public $name;
    private $groups = [];
    private $users = [];

    public function __construct(string $domain = null, $name = false)
    {
        if($domain)
        {
            $this->domain = $domain;
            $this->name = ($name ? $name : $domain);
        } else {
            throw new \NerdWerk\Exceptions\NerdWerkAuthenticationDomainException("Domain name not passed to constructor", 501);
        }
    }

    public function groupExists($name)
    {
        foreach($this->groups as $g)
        {
            if($g->getName() == $name)
            {
                return $g;
            }
        }
        return false;
    }

    public function createGroup($name, $permissions = [])
    {
        if(!isset($this->groups[$name]))
        {
            $this->groups[$name] = new Group($name, $permissions);
        }
        return $this->groups[$name];
    }

    public function userExists($username)
    {
        foreach($this->users as $u)
        {
            if($u->username == $username)
            {
                return $u;
            }
        }
        return false;
    }

    public function createUser($username, $password, $name = "", $email = "", $permissions = [], $groups = [])
    {
        if($u = $this->userExists($username))
        {
            return $u;
        } else {
            $u = new User($this, $username, hash((defined("NW_AUTHENTICATION_ALGORITHM") ? NW_AUTHENTICATION_ALGORITHM : "sha256"), $password), $name, $email, $permissions, $groups);
            $this->users[] = $u;
            return $u;
        }
    }

}