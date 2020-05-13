<?php

namespace NerdWerk;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;

class Events
{

    private $listeners = [];

    public function __construct(\NerdWerk\Config $config = null)
    {

        // Throw an exception if config not passed
        if(!$config)
        {
            throw new \NerdWerk\Exceptions\NerdWerkConfigException("Application configuration not passed to constructor", 100);
        }

        // Populate routes from annotations...
        AnnotationRegistry::registerLoader('class_exists');
        $reader = new CachedReader(
            new AnnotationReader(),
            new FilesystemCache(NW_CACHE_PATH.DIRECTORY_SEPARATOR."events"),
            $debug = (NW_ENVIRONMENT == "development")
        );
        $applicationPath = NW_APPLICATION_PATH.DIRECTORY_SEPARATOR;
        $applicationFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($applicationPath));
        foreach($applicationFiles as $file => $fileInfo)
        {
            if($fileInfo->isFile() && $fileInfo->getExtension() == "php")
            {
                $classNameRaw = array_filter(explode(DIRECTORY_SEPARATOR, str_replace([$applicationPath, ".php"], "", $fileInfo->getPathname())));
                $className = "\\NerdWerkApp\\".implode("\\", $classNameRaw);
                $reflectionClass = new \ReflectionClass($className);
                foreach($reflectionClass->getMethods() as $classMethod)
                {
                    $eventListenerAnnotations = $reader->getMethodAnnotation($classMethod, "\\NerdWerk\\Annotations\\EventListener");
                    if($eventListenerAnnotations instanceof \NerdWerk\Annotations\EventListener)
                    {
                        $this->listen($eventListenerAnnotations->event, [$classMethod->class, $classMethod->name]);
                    }
                }
            }
        }

        // Populate routes from config files...
        foreach($config->events as $event)
        {
            if(count(array_values($event)) >= 2)
            {
                $this->listen($event[0], $event[1]);
            } else {
                throw new \NerdWerk\Exceptions\NerdWerkEventListenerConfigurationNotValidException("Event configuration expects two parameters: event and function / callable", 301);
            }
        }

    }

    public function emit(string $event = null, $data = null)
    {
        if($event)
        {
            if(isset($this->listeners[$event]))
            {
                // Send the event and any data to the listeners...
                foreach($this->listeners[$event] as $e)
                {
                    call_user_func_array($e, $data);
                }
                return true;
            }
        }
        return false;
    }

    public function listen(string $event = null, $callback = null)
    {
        if($event && $callback)
        {
            if(is_callable($callback))
            {
                if(!isset($this->listeners[$event]))
                {
                    $this->listeners[$event] = [];
                }

                $this->listeners[$event][] = $callback;
                return true;
            }
        }
        throw new \NerdWerk\Exceptions\NerdWerkEventListenerConfigurationNotValidException("Event configuration expects two parameters: event and function / callable", 301);
    }

}