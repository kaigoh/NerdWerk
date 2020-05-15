<?php

$this->config['events'] = [
    [
        "event" => "framework_booted",
        "callback" => function() { header("X-NerdWerk-Status: framework-has-booted"); },
    ],
];