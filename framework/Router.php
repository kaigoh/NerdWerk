<?php

namespace NerdWerk;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

class Router
{
    public $router;

    public function __construct(\NerdWerk\Config $config = null)
    {
        // Initialise the routing engine...
        $this->router = new \Bramus\Router\Router();

        // Populate routes from annotations...
        AnnotationRegistry::registerLoader('class_exists');
        $reader = new AnnotationReader();
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
                        $this->router->match(strtoupper($routeAnnotations->method), $routeAnnotations->pattern, $classMethod->class."@".$classMethod->name);
                    }
                }
            }
        }

        // Populate routes from config files...
        foreach($config->routes as $route)
        {
            if(count(array_values($route)) >= 3)
            {
                $this->router->match(strtoupper($route[0]), $route[1], $route[2]);
            } else {
                throw new \NerdWerk\Exceptions\NerdWerkRouteConfigurationNotValidException("Route configuration expects at least three parameters: method, pattern and function / callable", 201);
            }
        }

        // Run the configured routes
        $this->router->run();
    }

}