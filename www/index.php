<?php

use Zule\Tools\Router;
use Zule\Controllers\Error;

require_once '../tools/Main.php';

try
{
    $controller = getController();
    $action = getAction();
    
    if ($controller->isValidAction($action))
    {
        call_user_func(array($controller, $action));
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
