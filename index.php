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
 * Run the framework
 */
$framework = new NerdWerk\Bootstrap();