<?php

namespace NerdWerk\Authentication\CredentialSources;

interface CredentialSourceInterface
{
    public function getCredentials() : ?\NerdWerk\Authentication\Credentials;
}