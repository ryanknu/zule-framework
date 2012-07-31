<?php

namespace Zule\Tools;

use Zule\Controllers\Error;

class Router
{
    private $components;
    private $controller;
    private $requestURI;
    
    public static function Router()
    {
        static $_router = null;
        if ( $_router === null )
        {
            $_router = new Router;
        }
        return $_router;
    }
    
    private function __construct()
    {
        $uri = substr($_SERVER['REQUEST_URI'], 1);
        $this->requestURI = $uri;
        if ( strpos($uri, '?') )
        {
            $uri = strstr($uri, '?', yes);
        }
        $this->components = explode('/', $uri);
        $this->controller = null;
    }
    
    public function getRequest()
    {
        return $this->requestURI;
    }
    
    public function getController()
    {
        if ( $this->controller === null )
        {
            if ( !empty($this->components[0]) )
            {
                $controllerName = $this->components[0];
                $this->controller = $this->loadController($controllerName);
            }
            else
            {
                $indexController = (new Config)->get('index:controller');
                $this->controller = $this->loadController($indexController);
            }
        }
        return $this->controller;
    }
    
    public function getAction()
    {
        if ( !empty($this->components[1]) )
        {
            return $this->components[1];
        }
        return (new Config)->get('index:action');
    }
    
    public function getComponents()
    {
        return $this->components;
    }
    
    public function getArguments()
    {
        $components = [];
        for( $i = 2; $i < count($this->components); ++$i )
            $components[] = $this->components[$i];
            
        return $components;
    }
    
    public function redirect($location)
    {
        if ( headers_sent() )
        {
            echo '<script type="text/javascript">';
            echo "document.location = \"$location\";";
            echo '</script>';
        }
        else
        {
            header("Location: $location");
        }
    }
    
    private function loadController($name)
	{
	    // Use a wildcard identifier
	    $id = new Identifier("\\*\\Controllers\\$name");
	    $class = $id->getClassName();
	    if ( $class )
	    {
	        return new $class($name, $this);
	    }
	    else
	    {
	        return new Error('Error', $this);
	    }
	}
}

