<?php

namespace NerdWerk\Authentication;

class Credentials
{

    public $domain;
    public $username;
    public $password;

    public function __construct(?string $domain = null, string $username = null, string $password = null)
    {
        $this->domain = ($domain ? $domain : "root");
        $this->username = $username;
        $this->password = $password;
    }

}