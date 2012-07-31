<?php

use Zule\Tools\Router;

define('no', 0);
define('yes', 1);

// Normalize project installation directory
define('ROOT', str_replace('\\','/',realpath(dirname(__FILE__).'/..')).'/');

// TODO: Timezone setting: should change to vital
date_default_timezone_set('America/Chicago');

// get vital config
require ROOT . 'config/vital.php';

// get implier
require ROOT . 'tools/Imply.php';

// global router functions
function getController()
{
    return Router::Router()->getController();
}

function getAction()
{
    return Router::Router()->getAction();
}

function getRequest()
{
    return Router::Router()->getRequest();
}
