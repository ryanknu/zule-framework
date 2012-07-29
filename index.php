<?php

namespace Zule;

require_once 'tools/Main.php';

if ( isset( $_GET['show_route_info'] ) )
{
    (new \Zule\Controllers\Error)->Action404();
}

try
{
    $controller = \Zule\Tools\Router::Router()->getController();
    $action = \Zule\Tools\Router::Router()->getAction();
    
    if ($controller->canRespondToAction($action))
    {
        call_user_func(array($controller, $action));
    }
    else
    {
        (new \Zule\Controllers\Error)->Action404();
    }
}
catch(\Exception $e)
{
    $c = new \Zule\Controllers\Error;
    $c->setException($e);
    $c->Action500();
}
