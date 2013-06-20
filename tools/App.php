<?php

namespace Zule\Tools;
use Zule\Controllers\Error;
use \Exception;

class App
{
    public function dispatch() {

        try
        {
            $controller = Router::Router()->getController();
            $action = Router::Router()->getAction();
            
            if ( $controller->preRunFunction )
            {
            	call_user_func([$controller, $controller->preRunFunction]);
            }
            
            if ($controller->canRespondToAction($action))
            {
                call_user_func([$controller, $action]);
            }
            else
            {
                (new Error)->Action404();
            }
        }
        catch(Exception $e)
        {
            $c = new Error;
            $c->setException($e);
            $c->Action500();
        }       
    }
}
