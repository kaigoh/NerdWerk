<?php

namespace NerdWerk\Authentication;

abstract class AuthenticationProvider implements \NerdWerk\Interfaces\AuthenticationProvider
{
    public abstract function authenticate($domain, $username, $password) : ?\NerdWerk\Authentication\User;
}