<?php

namespace Zule\Controllers;

class Index extends Controller
{
    protected $actions = array('IndexAction');
    
    public function IndexAction()
    {
        $s = new \Smarty;
        
        $s->assign('title', 'Zule Framework');
        $s->display(\Zule\Tools\View::find('header'));
        $s->display(\Zule\Tools\view::find('home'));
        $s->display(\Zule\Tools\view::find('footer'));
    }
    
}
