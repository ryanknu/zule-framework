<?php

namespace Zule\Controllers;

class Index extends Controller
{
    protected $actions = ['IndexAction'];
    
    public function IndexAction()
    {
        $view = $this->getView();
        
        $view->assign('title', 'Zule Framework');
        $view->display('header');
        $view->display('home');
        $view->display('footer');
    }
    
}
