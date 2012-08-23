<?php

namespace Zule\Controllers;

class Index extends Controller
{   
    public function IndexAction()
    {
        $view = $this->getView();
        (new \Zule\Tools\Language)->setLanguage('cn');
        
        $view->assign('title', 'Zule Framework');
        
        $view->display('header');
        $view->display('home');
        $view->display('footer');
    }
}
