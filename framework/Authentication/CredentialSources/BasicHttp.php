<?php

namespace NerdWerk\Authentication\CredentialSources;

use \NerdWerk\Authentication\Credentials as Credentials;

class BasicHttp extends \NerdWerk\Authentication\CredentialSource
{

    private $realm;
    private $credentials;

    public function __construct(string $realm = null)
    {
        $this->realm = ($realm ? $realm : "Authenticate");
    }

    private function collectCredentials()
    {
        if(!isset($_SERVER['PHP_AUTH_USER']))
        {
            header('WWW-Authenticate: Basic realm="'.$this->realm.'"');
            header('HTTP/1.0 401 Unauthorized');
            die("Unauthorised");
        } else {
            // Did we get a domain with the username?
            $du = explode("|", $_SERVER['PHP_AUTH_USER'], 2);
            $domain = (count($du) == 2 ? $du[0] : "root");
            $username = (count($du) == 2 ? $du[1] : $du[0]);
            $this->credentials = new Credentials($domain, $username, hash((defined("NW_AUTHENTICATION_ALGORITHM") ? NW_AUTHENTICATION_ALGORITHM : "sha256"), $_SERVER['PHP_AUTH_PW']));
        }
    }

    public function getCredentials() : \NerdWerk\Authentication\Credentials
    {
        if(!$this->credentials)
        {
            $this->collectCredentials();
        }
        return $this->credentials;
    }

}