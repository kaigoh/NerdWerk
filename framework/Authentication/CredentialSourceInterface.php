<?php

namespace NerdWerk\Authentication;

interface CredentialSourceInterface
{
    public function getCredentials() : \NerdWerk\Authentication\Credentials;
}