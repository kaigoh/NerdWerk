<?php

namespace NerdWerk;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\FilesystemCache;

class Router
{

    private $framework;
    private $router;
    public $http404Callback = null;

    public function __construct(\NerdWerk\Framework &$framework = null, \NerdWerk\Config $config = null, $authenticationProviders = [])
    {

        // Throw an exception if config not passed
        if(!$config)
        {
            throw new \NerdWerk\Exceptions\ConfigException("Application configuration not passed to constructor", 100);
        }

        /**
         * CLI Routing
         * 
         * Set some properties in the $_SERVER array that are missing when the script is called from the CLI
         */
        if(php_sapi_name() == "cli")
        {
            global $argv;
            $_SERVER['REQUEST_METHOD'] = "GET";
            $_SERVER['SERVER_PROTOCOL'] = "HTTP/1.1";
            // Make the CLI arguments look like a URL...
            $_SERVER['REQUEST_URI'] = "/".implode("/", array_slice($argv, 1));
        }

        $this->framework = $framework;

        // Initialise the routing engine...
        $this->router = new \Bramus\Router\Router();

        // Set the 404 handler
        $this->router->set404(function() use ($framework)
        {
            // Try and hand the 404 error off to the applications 404 handler,
            // otherwise give a canned response...
            if(class_exists("\NerdWerkApp\Http\Response404"))
            {
                $framework->response = new \NerdWerkApp\Http\Response404();
            } else {
                $framework->response = new \NerdWerk\Http\Response(404);
            }
        });

        // Populate routes from annotations...
        AnnotationRegistry::registerLoader('class_exists');
        $reader = new CachedReader(
            new AnnotationReader(),
            new FilesystemCache(NW_CACHE_PATH.DIRECTORY_SEPARATOR."router"),
            $debug = (NW_ENVIRONMENT == "development")
        );
        $controllerPath = NW_APPLICATION_PATH.DIRECTORY_SEPARATOR."controllers";
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
                        $this->addRoute($this->framework, $routeAnnotations, [$classMethod->class, $classMethod->name]);
                    }
                }
            }
        }

        // Populate routes from config files...
        foreach($config->routes as $route)
        {
            $this->addRoute($this->framework, \NerdWerk\Annotations\Route::fromArray($route));
        }
    }

    private function addRoute(\NerdWerk\Framework $framework, \NerdWerk\Annotations\Route $routeAnnotations, $callback = null)
    {
        if(!$callback)
        {
            $callback = $routeAnnotations->callback;
        }
        if($routeAnnotations->method)
        {
            $this->router->match(strtoupper($routeAnnotations->method), $routeAnnotations->pattern, function() use ($framework, $callback)
            {
                $framework->response = call_user_func_array($callback, array_merge(func_get_args(), [&$framework]));
            });
            if($routeAnnotations->authenticationRequired)
            {
                $this->router->before(strtoupper($routeAnnotations->method), $routeAnnotations->pattern, function() use ($framework, $routeAnnotations)
                {
                    if(!$framework->getUser() || ($routeAnnotations->authenticationPermission && !$framework->getUser()->hasPermission($routeAnnotations->authenticationPermission)))
                    {
                        // Try and hand the 403 error off to the applications 403 handler,
                        // otherwise give a canned response...
                        if(class_exists("\NerdWerkApp\Http\Response403"))
                        {
                            $framework->response = new \NerdWerkApp\Http\Response403();
                        } else {
                            $framework->response = new \NerdWerk\Http\Response(403);
                            $framework->end();
                        }
                    }
                });
            }
        } else {
            if(!$routeAnnotations->authenticationRequired)
            {
                $this->router->all($routeAnnotations->pattern, function() use ($framework, $callback)
                {
                    $framework->response = call_user_func_array($callback, array_merge(func_get_args(), [&$framework]));
                });
            } else {
                throw new \NerdWerk\Exceptions\RouteConfigurationNotValidException("Routes using authentication providers must specify route HTTP verb (i.e. GET, POST or multiple verbs GET|POST)", 202);
            }
        }
    }

    // Run the configured routes
    public function route()
    {
        $this->router->run();
    }

}