<?php

$this->config['routes'] = [
    // Format is [method, pattern, callable / anonymous function]
    ["get", "/framework/home", "\NerdWerkApp\Controllers\Home@index"],
    ["get", "/framework/version/{test}", function($test = false) { echo "Welcome to NerdWerk :)".($test ? $test : "")."\r\n"; }],
];