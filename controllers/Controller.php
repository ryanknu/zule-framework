<?php

namespace Zule\Controllers;

class Controller
{
    protected $actions = array();
    protected $name = "";
    
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function canRespondToAction($action)
    {
        return in_array($action, $this->actions);
    }
    
    public function getActions()
    {
        return $this->actions;
    }
    
}
