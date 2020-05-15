<?php

$this->config['routes'] = [
    [
        "method" => "get",
        "pattern" => "/framework/home",
        "callback" => ["\NerdWerkApp\Controllers\Home", "index"],
    ],
    [
        "method" => "get",
        "pattern" => "/framework/version/{test}",
        "callback" => function($test = false)
        {
            echo "Welcome to NerdWerk :)".($test ? $test : "")."\r\n";
        }
    ],
];