<?php

namespace Zule\Controllers;

class Error extends Controller
{
    protected $actions = [];
    protected $name = 'Error';
    private $exception;
    
    public function Action404()
    {
        $view = $this->getView();
        if ( \Zule\Tools\Config::dev() )
        {
            $view->assign('message', print_r(\Zule\Tools\Router::Router()->getRoutesInfo(), true));
        }
        else
        {
            $view->assign('message', 'The resource requested could not be located.');
        }
        $view->display('404');
    }
    
    public function setException($e)
    {
        $this->exception = $e;
    }
    
    public function Action500()
    {
        $view = $this->getView();
        $view->assign('message', $this->exception->__toString());
        $view->display('500');
    }
    
}
