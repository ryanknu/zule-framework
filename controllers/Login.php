<?php

namespace Guest\Controllers;

use Smarty;
use Zule\Tools\Form;
use Zule\Controllers\Controller;

class Login extends Controller
{   
        
    public function IndexAction()
    {
        static $USER_ID = 'user';
        static $PASS_ID = 'pass';
        
        $form = new Form;
        
        // The form's name only specifies how it is rendered.
        $form->setName('Login');
        
        // The ZF implements a lightweight input type method to determine
        // how to draw elements. To override this, you can call-chain
        // ->drawAs(string) to addInput.
        $form->addInput($USER_ID, ZF_EMAIL);
        $form->addInput($PASS_ID, ZF_MASKED);
        
        // Some validation methods can be call-chained off of the addInput
        // method for convenience. You can call-chain ->matches to ensure
        // that the user enters the same value in two input elements.
        $form->addInput('pass2', ZF_MASKED)->matches('pass');
        
        // You can call-chain ->setDefault to set the value="" box on most
        // standard form implementations. This depends entirely on your
        // custom form layout, however.
        $form->addInput('xxx', ZF_SUBMIT)->setDefault('Submit Form')->drawAs('bomb.tpl');
        
        if ( $form->isFilled() )
        {
            $results = $form->getResults();
            
            // user is a filled, validated, and sanitized email address.
            $user = $results[$USER_ID];
            
            // pass is a password value that has been typed in twice.
            $pass = $results[$PASS_ID];
            
            echo "user=$user & pass=$pass";
        }
        else
        {
            // show form
            $s = $this->getView();
            $s->assign('form', $form->getHtml());
            $s->display('Form');
        }
    }
    
    
}
