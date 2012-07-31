<?php

namespace Guest\Controllers;

class Sign extends \Zule\Controllers\Controller
{   
        
    public function IndexAction()
    {
        //throw new \Zule\Tools\Exception('This is my exception');
        $s = $this->getView();
        $s->assign('controller', 'Sign');
        $s->assign('action', 'IndexAction');
        $s->display('IndexAction');
    }
    
        
    public function Save()
    {
        $message = new \Guest\Models\Message;
        $message->setKey($_POST['from']);
        if ( !$message->getGateway()->exists($_POST['from']) )
            $message->save($_POST);
        header('Location: /');  
    }
    
    
}
