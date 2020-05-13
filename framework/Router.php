<?php

namespace NerdWerk;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;

class Router
{
    private $router;

    public function __construct(\NerdWerk\Config $config = null)
    {
        // Throw an exception if config not passed
        if(!$config)
        {
            throw new \NerdWerk\Exceptions\NerdWerkConfigException("Application configuration not passed to constructor", 100);
        }

        // Initialise the routing engine...
        $this->router = new \Bramus\Router\Router();

        // Populate routes from annotations...
        AnnotationRegistry::registerLoader('class_exists');
        $reader = new CachedReader(
            new AnnotationReader(),
            new FilesystemCache(NW_CACHE_PATH.DIRECTORY_SEPARATOR."router"),
            $debug = (NW_ENVIRONMENT == "development")
        );
        $controllerPath = NW_APPLICATION_PATH.DIRECTORY_SEPARATOR."Controllers";
        $controllerFiles = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($controllerPath));
        foreach($controllerFiles as $file => $fileInfo)
        {
            if($fileInfo->isFile() && $fileInfo->getExtension() == "php")
            {
                $classNameRaw = array_filter(explode(DIRECTORY_SEPARATOR, str_replace([$controllerPath, ".php"], "", $fileInfo->getPathname())));
                $className = "\\NerdWerkApp\\Controllers\\".implode("\\", $classNameRaw);
                $reflectionClass = new \ReflectionClass($className);
                foreach($reflectionClass->getMethods() as $classMethod)
                {
                    $routeAnnotations = $reader->getMethodAnnotation($classMethod, "\\NerdWerk\\Annotations\\Route");
                    if($routeAnnotations instanceof \NerdWerk\Annotations\Route)
                    {
                        if($routeAnnotations->method)
                        {
                            $this->router->match(strtoupper($routeAnnotations->method), $routeAnnotations->pattern, $classMethod->class."@".$classMethod->name);
                        } else {
                            $this->router->all($routeAnnotations->pattern, $classMethod->class."@".$classMethod->name);
                        }
                    }
                }
            }
        }

        // Populate routes from config files...
        foreach($config->routes as $route)
        {
            switch(count(array_values($route)))
            {
                case 2:
                    $this->router->all($route[0], $route[1]);
                break;

                case 3:
                    $this->router->match(strtoupper($route[0]), $route[1], $route[2]);
                break;

                default:
                    throw new \NerdWerk\Exceptions\NerdWerkRouteConfigurationNotValidException("Route configuration expects two (pattern and function / callable) or three parameters (method, pattern and function / callable)", 201);
            }
        }
    }

    // Run the configured routes
    public function route()
    {
        $this->router->run();
    }

}