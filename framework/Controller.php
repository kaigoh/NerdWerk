<?php

namespace NerdWerk;

use \NerdWerk\Annotations\Route as Route;

class Controller
{
    protected $framework;
    protected $input;

    public function __construct()
    {
        $this->framework = GetFramework();
        $this->input = $this->framework->input;
    }

    protected function emit($event, $data)
    {
        $this->framework->events->emit($event, $data);
    }

}