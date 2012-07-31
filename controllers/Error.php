<?php

namespace Zule\Controllers;

use Zule\Tools\Router;
use Zule\Tools\Config;

class Error extends Controller
{
    private $exception;
    
    public function __construct()
    {
        parent::__construct('Error', Router::Router());
    }
    
    public function Action404()
    {
        $view = $this->getView();
        $view->assign('message', 'The resource you requested could not be located.');
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
