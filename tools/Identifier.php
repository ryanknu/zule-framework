<?php

namespace Zule\Tools;

class Identifier
{
    private $before;
    private $after;
    private static $spaces = [];
    
    public function __construct($id)
    {
        $this->before = '';
        $this->after = '';
        if ( !self::$spaces )
        {
            self::$spaces = [(new Config)->get('namespace'), 'Zule'];
        }
        
        $pivot = strpos($id, '*');
        $this->before = substr($id, 0, $pivot);
        $this->after = substr($id, $pivot + 1);
        
        if ( !$this->getClassName() )
        {
            Imply($id);
        }
    }
    
    public function getClassName()
    {
        foreach(self::$spaces as $space)
        {
            $class = ($this->before . $space . $this->after);
            if ( class_exists($class, no) )
            {
                return $class;
            }
        }
        return no;
    }
}