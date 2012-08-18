<?php

namespace Zule\Controllers;

class Controller
{
    private $actions = [];
    protected $name = '';
    public $preRunFunction = '';
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    private function loadActionsByReflection()
    {
        if ( empty( $this->actions ) )
        {
            $class = get_class($this);
            $rc = new \ReflectionClass($class);
            $methods = $rc->getMethods(\ReflectionMethod::IS_PUBLIC);
            foreach( $methods as $method )
            {
                if ( $method->getDeclaringClass()->getName() == $class )
                {
                    // strip out inherited methods
                    if ( $method->getName() <> $this->preRunFunction )
                    {
                    	$this->actions[] = $method->getName();
                    }
                }
            }
        }
    }
    
    public function canRespondToAction($action)
    {
        $this->loadActionsByReflection();
        return in_array($action, $this->actions);
    }
    
    public function getActions()
    {
        $this->loadActionsByReflection();
        return $this->actions;
    }
    
    public function getView()
    {
        return new \Zule\Tools\View;
    }
    
}
