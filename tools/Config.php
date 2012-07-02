<?php

namespace Zule\Tools;

require_once "Zend/Config/Xml.php";

class Config
{
	private function __construct() { }
	
	public static function File()
	{
	    return ROOT . 'tools/config/config.xml';
	}
	
	public static function zc()
	{
	    static $_config = null;
	    if ( $_config === null )
	    {
	        $_config = new \Zend_Config_Xml(self::File());
	    }
	    return $_config;
	}
	
	public static function dev()
	{
	    if ( self::zc()->framework->dev == 'yes' )
	        return yes;
	    return no;
	}
}

