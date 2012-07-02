<?php

namespace Zule\Tools;

class View
{
    public static function find($name)
    {
        $controller = Router::Router()->getController()->getName();
        if ( $controller )
        {
            // First, try controller path.
            $path = ROOT . "views/$controller/$name.tpl";
            if ( file_exists( $path ) )
                return $path;
        }

        $stdPath = ROOT . 'views/' . $name . '.tpl';
        if ( ! file_exists( $stdPath ) )
        {
            return ROOT . 'views/404.tpl';
        }
        return $stdPath;
    }
}
