<?php

namespace Zule\Controllers;

use ReflectionClass;
use ReflectionMethod;
use Zule\Tools\View;
use Zule\Tools\Router;

class Controller
{
    private $actions = [];
    protected $router = null;
    protected $name = "";
    
    public function __construct($name, Router $router)
    {
        $this->name = $name;
        $this->router = $router;
    }
    
    private function loadActionsByReflection()
    {
        if ( empty( $this->actions ) )
        {
            $class = get_class($this);
            $rc = new ReflectionClass($class);
            $methods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
            foreach( $methods as $method )
            {
                if ( $method->getDeclaringClass()->getName() == $class )
                {
                    // strip out inherited methods
                    $this->actions[] = $method->getName();
                }
            }
        }
    }
    
    public function isValidAction($action)
    {
        $this->loadActionsByReflection();
        return in_array($action, $this->actions);
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function getView()
    {
        return new View($this->name);
    }
    
    public function getRouter()
    {
        return $this->router;
    }
    
}
