<?php

namespace NerdWerk\Authentication;

abstract class CredentialSource implements CredentialSourceInterface
{

    public abstract function getCredentials() : \NerdWerk\Authentication\Credentials;

}