<?php

namespace NerdWerk\Interfaces;

interface CredentialSource
{
    public function getCredentials() : ?\NerdWerk\Authentication\Credentials;
}