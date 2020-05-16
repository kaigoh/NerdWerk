<?php

/**
 * Composer autoloader
 */
require __DIR__."/vendor/autoload.php";

/**
 * Load the environment file, if exists...
 */
if(file_exists(".env"))
{
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

/**
 * Function to allow a reference to the Framework object to be obtained - Do not remove!
 */
function GetFramework()
{
    global $framework;
    return $framework->getInstance();
}

/**
 * Initialise the framework...
 */

$framework = new NerdWerk\Framework();

/**
 * ...add an authentication provider...
 */
$framework->addAuthenticationProvider(new NerdWerk\Authentication\AuthenticationProviders\ConfigFile($framework->config));

/**
 * ...and a credential source...
 */
$framework->addCredentialSource(new NerdWerk\Authentication\CredentialSources\BasicHttp("NerdWerk Framework"));

/**
 * ...and run it
 */
$framework->run();