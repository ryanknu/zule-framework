<?php

namespace Zule\Tools;

require 'Predis/Autoloader.php';
\Predis\Autoloader::register();

class Predis
{
    private static $adapters = array();
    
	private function __construct() { }
	
	static function instance($named = 'default')
	{
        if (!isset($self->adapters[$named]))
        {
            // todo configure predis client.
            // right now I'm just trying to make it work :D
            self::$adapters[$named] = new \Predis\Client;
        }
        
        return self::$adapters[$named];
	}
}

