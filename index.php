<?php

// Load the Composer autoloader...
require __DIR__ . '/vendor/autoload.php';

// Load the .env file...
if(file_exists(".env"))
{
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
}

// Away we go...
$boot = new NerdWerk\Bootstrap();