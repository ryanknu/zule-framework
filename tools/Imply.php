<?php

namespace Zule\Tools;

// Adds an auto-loader for auto-awesome.
// Needs config for custom application namespaces.

function Imply($class)
{
    if ( substr_count($class, '\\') > 1 )
    {
        $pivot = strpos($class, '\\', 1);
        $path = substr($class, $pivot + 1);
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
        $path = ROOT . $path . '.php';
        if ( file_exists( $path ) )
        {
            require $path;
        }
    }
}

spl_autoload_register( '\\Zule\\Tools\\Imply' );
