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
 * Framework Constants
 */
define("NW_FRAMEWORK_PATH", "framework"); // You shouldn't need to ever change this, but just in case...
define("NW_APPLICATION_PATH", "application"); // You shouldn't need to ever change this, but just in case...
define("NW_CONFIG_PATH", "config"); // You shouldn't need to ever change this, but just in case...
define("NW_CACHE_PATH", "cache"); // You shouldn't need to ever change this, but just in case...
define("NW_ENVIRONMENT", "development"); // Change this to "production" once you are ready!

/**
 * CLI Routing
 * 
 * Set some properties in the $_SERVER array that are missing when the script is called from the CLI
 */
if(php_sapi_name() == "cli")
{
    $_SERVER['REQUEST_METHOD'] = "GET";
    $_SERVER['SERVER_PROTOCOL'] = "HTTP/1.1";
    // Make the CLI arguments look like a URL...
    $_SERVER['REQUEST_URI'] = "/".implode("/", array_slice($argv, 1));
}

/**
 * Initialise the framework...
 */

$framework = new NerdWerk\Framework();

/**
 * ...and run it
 */
$framework->start();

/**
 * Function to allow a reference to the Framework object to be obtained
 */
function GetFramework()
{
    global $framework;
    return $framework;
}