<?php

namespace NerdWerk;

use \NerdWerk\Annotations\Route as Route;

class Controller
{
    protected $framework;

    public function __construct()
    {
        global $framework;
        $this->framework = $framework;
    }

}