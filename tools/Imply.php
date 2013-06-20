<?php

namespace Zule\Tools;

// Adds an auto-loader for auto-awesome.
// Needs config for custom application namespaces.
require_once ROOT . 'tools/Config.php';

spl_autoload_register( function($class) {
    if ( $file = \Zule\Tools\Imply::classCanBeImplied($class) )
    {
        require_once $file;
    }
} );

class Imply
{
    // This function should only be called by the implier and functions within
    // the framework. It is not intended for general consumption.
    public static function classCanBeImplied($name)
    {
        static $namespaces = ['Zule'];
        
        if ( $name{0} == '\\' )
        {
            // For whole identifiers, e.g. \Zule\Test\Whatever, we need to cut off
            // the first character for this function to work.
            $name = substr($name, 1);
        }
        
        if ( count($namespaces) == 1 )
        {
            // Cannot statically declare dynamic variable, need to set at runtime.
            $namespaces[] = (new Config)->get('namespace');
        }
        
        $pieces = explode('\\', $name);
        if ( count($pieces) && in_array($pieces[0], $namespaces) )
        {
            $file = self::getFileNameForClass($name);
            
            if ( file_exists( $file ) )
            {
                return $file;
            }
        }
        return no;
    }
    
    public static function getFileNameForClass($class)
    {
        if ( $class{0} == '\\' )
        {
            // For whole identifiers, e.g. \Zule\Test\Whatever, we need to cut off
            // the first character for this function to work.
            $class = substr($class, 1);
        }
        
        $pieces = explode('\\', $class);
        if ( count($pieces) > 2 )
        {
            $r = $pieces[2];
            for ( $the_i = 3; $the_i < count($pieces); $the_i++ )
            {
                $r .= (DIRECTORY_SEPARATOR . $pieces[$the_i]);
            }
            return ROOT . strtolower($pieces[1]) . DIRECTORY_SEPARATOR . "$r.php";
        }
        throw new Exception("Class namespace depth too short for autoloader ($class)");
    }
}
