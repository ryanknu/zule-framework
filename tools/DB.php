<?php

namespace Zule\Tools;

require_once "Zend/Db.php";

class DB
{
    private static $adapters = array();
    
	private function __construct() { }
	
	static function zdb()
	{
		static $db = NULL;
		if ( $db === NULL )
		{
			$config = Config::zc();
			$db = \Zend_Db::factory($config->default->database);
		}
		return $db;
	}
	
	static function adapter($connectionName = '')
	{
        if (!isset($self->adapters[$connectionName]))
        {
            if ( $connectionName ) 
            {
                $connectionName = '_' . $connectionName;
            }
            $paramName = 'database' . $connectionName;      
            
            $config = Config::zc()->default->get($paramName);
            
            self::$adapters[$connectionName] = \Zend_Db::factory($config);
            self::$adapters[$connectionName]->setFetchMode(\Zend_Db::FETCH_ASSOC);    
        }
        
        return self::$adapters[$connectionName];
	}
}

