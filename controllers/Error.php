<?php

namespace Zule\Controllers;

class Error extends Controller
{
    protected $name = 'Error';
    private $exception;
    
    public function Action404()
    {
        $view = $this->getView();
        if ( (new Config)->get('dev') )
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
        if ( (new Config)->get('dev') )
        {
            $view->assign('message', $this->exception->__toString());
        }
        else
        {
            $view->assign('message', 'There was a problem, and the resource cannot be displayed.');
        }
        $view->display('500');
    }
    
}
