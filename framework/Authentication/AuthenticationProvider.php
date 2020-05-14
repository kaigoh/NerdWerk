<?php

namespace NerdWerk\Authentication;

abstract class AuthenticationProvider implements AuthenticationProviderInterface
{
    public abstract function authenticate($domain, $username, $password) : ?\NerdWerk\Authentication\User;
}