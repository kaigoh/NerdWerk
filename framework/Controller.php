<?php

namespace NerdWerk;

use \NerdWerk\Annotations\Route as Route;

class Controller
{
    protected $framework;

    public function __construct()
    {
        $this->framework = GetFramework();
    }

    protected function emit($event, $data)
    {
        $this->framework->events->emit($event, $data);
    }

}