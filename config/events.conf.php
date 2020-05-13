<?php

$this->config['events'] = [
    // Format is [event, callable / anonymous function]
    ["framework_booted", function() { echo "Framework has booted!"; }],
];