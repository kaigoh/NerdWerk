<?php

namespace NerdWerk\Interfaces;

interface AuthenticationProvider
{
    public function authenticate($domain, $username, $password) : ?\NerdWerk\Authentication\User;
}