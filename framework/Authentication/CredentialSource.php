<?php

namespace NerdWerk\Authentication;

abstract class CredentialSource implements \NerdWerk\Interfaces\CredentialSource
{

    public abstract function getCredentials() : \NerdWerk\Authentication\Credentials;

}