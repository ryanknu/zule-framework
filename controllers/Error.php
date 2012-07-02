<?php

namespace Zule\Controllers;

class Error extends Controller
{
    protected $actions = array('Action404', 'Action500');
    protected $name = 'Error';
    private $exception;
    
    public function Action404()
    {
        $s = new \Smarty;
        if ( \Zule\Tools\Config::dev() )
        {
            $s->assign('message', print_r(\Zule\Tools\Router::Router()->getRoutesInfo(), true));
        }
        else
        {
            $s->assign('message', 'The resource requested could not be located.');
        }
        $s->display(\Zule\Tools\View::find('404'));
    }
    
    public function setException($e)
    {
        $this->exception = $e;
    }
    
    public function Action500()
    {
        $s = new \Smarty;
        $s->assign('message', $this->exception->__toString());
        $s->display(\Zule\Tools\View::find('500'));
    }
    
}
