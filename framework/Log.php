<?php

namespace NerdWerk;

use \Monolog\Logger;
use \Monolog\Handler\RotatingFileHandler;

class Log
{

    private $channel;
    private $log;

    public function __construct(string $channel = "nerdwerk")
    {
        $this->channel = $channel;
        $this->log = new \Monolog\Logger($channel);
        $this->log->pushHandler(new \Monolog\Handler\RotatingFileHandler(NW_LOG_PATH.DIRECTORY_SEPARATOR."nerdwerk-log-".$channel), 7);
        $this->log->pushProcessor(new \Monolog\Processor\IntrospectionProcessor());
        $this->log->pushProcessor(new \Monolog\Processor\WebProcessor());
        $this->log->pushProcessor(new \Monolog\Processor\MemoryUsageProcessor());
    }

    public function __call($name, $args)
    {
        return call_user_func_array([$this->log, $name], $args);
    }

}