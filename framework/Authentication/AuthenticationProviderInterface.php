<?php

namespace NerdWerk\Authentication;

interface AuthenticationProviderInterface
{
    public function authenticate($domain, $username, $password) : ?\NerdWerk\Authentication\User;
}