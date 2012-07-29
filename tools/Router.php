<?php

namespace Zule\Tools;

class Router
{
    private $components;
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
    }
    
    public function getRequest()
    {
        return $this->requestURI;
    }
    
    public function getController()
    {
        if ( !empty($this->components[0]) )
        {
            $controllerName = $this->components[0];
            return $this->loadController($controllerName);
        }
        else
        {
            $indexController = (new Config)->get('index:controller');
            return $this->loadController($indexController);
        }
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
    
    public function getRoutesInfo()
    {
        $controller = $this->getController();
        $action = @$this->components[1];
        $canAct = $controller->canRespondToAction($action)? 'yes':'no';
        if ( $canAct == 'no' ) {
            $controller = new \Zule\Controllers\Error;
            $action = 'Action404';
        }
        $className = $this->getControllerClassName($this->components[0]);
        $classLoaded = class_exists($className)? 'yes':'no';
        if ( $classLoaded == 'yes' )
        {
            $c = new $className;
            $controllerActions = json_encode($c->getActions());
        }
        else
        {
            $controllerActions = '[]';
        }
        return [
            'uri' => substr($_SERVER['REQUEST_URI'], 1),
            'route_controller' => $this->components[0],
            'route_action' => @$this->components[1],
            'index_controller' => (new Config)->get('index:controller'),
            'index_action' => (new Config)->get('index:action'),
            'using_index_controller' => empty($this->components[0]) ? 'yes':'yes',
            'using_index_action' => (empty($this->components[1]))?'yes':'no',
            'controller_class' => $className,
            'looking_in_file' => Imply::getFileNameForClass($className),
            'controller_class_exists' => $classLoaded,
            'route_matched' => $canAct,
            'loaded_controller' => $controller->getName(),
            'loaded_action' => $action,
            'query_string' => $_SERVER['QUERY_STRING'],
            'controller_actions' => $controllerActions,
        ];
    }
    
    private function getControllerClassName($name)
    {
        if ( empty($name) )
        {
            $name = (new Config)->get('index:controller');
        }
        $nameSpace = (new Config)->get('namespace');
	    $class = "\\$nameSpace\\Controllers\\$name";
	    return $class;
    }
    
    private function loadController($name)
	{
	    $class = $this->getControllerClassName($name);
	    if ( !Imply::classCanBeImplied($class) )
	    {
	        return new \Zule\Controllers\Error;
	    }
	    else
	    {	    
            if ( !class_exists($class) )
            {
                // try default namespace
                $str = substr($class, strpos($class, '\\', 1));
                $class = '\\Zule' . $str;
            }
            $controller = new $class;
            $controller->setName($name);
            return $controller;
	    }
	}
}

