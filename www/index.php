<?php

namespace Zule;
use Zule\Tools\Router;
use Zule\Controllers\Error;
use \Exception;

require_once '../tools/Main.php';

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
