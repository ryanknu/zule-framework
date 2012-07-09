<?php

namespace Zule\Tools;

define('ZF_VIEW_404', 'views/404.tpl');

class View
{
    // handle to a smarty object
    private $smarty;
    
    public function __construct()
    {
        $this->smarty = new \Smarty;
    }
    
    public function getSmarty()
    {
        return $this->smarty;
    }
        
    // Replicate Smarty methods for convenience
    public function assign($key, $value)
    {
        return $this->smarty->assign($key, $value);
    }
    
    public function display($tplName)
    {
        return $this->smarty->display(
            $this->find($tplName)
        );
    }
    
    public function fetch($tplName)
    {
        return $this->smarty->fetch(
            $this->find($tplName)
        );
    }
    
    // Find ensures that views are encapsulated between controllers.
    // It is not important which controller is doing the drawing but which
    // controller is specified by the router (in the URL).
    // Views may be in subdirectories for organization, but only if they're
    // distinguished at the controller level. Any views that need to be shared
    // between controllers must be specified at the root level and cannot
    // be sorted by directory.
    private function find($name)
    {
        $controller = Router::Router()->getController()->getName();
        if ( $controller )
        {
            // First, try controller path.
            $path = ROOT . "views/$controller/$name.tpl";
            if ( file_exists( $path ) )
            {
                return $path;
            }
            else if ( strpos($name, '/') === false )
            {
                return ROOT . ZF_VIEW_404;
            }
        }

        $stdPath = ROOT . 'views/' . $name . '.tpl';
        if ( ! file_exists( $stdPath ) )
        {
            return ROOT . ZF_VIEW_404;
        }
        return $stdPath;
    }
}
