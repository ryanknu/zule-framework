<?php

namespace Zule\Tools;

define('no', 0);
define('yes', 1);

// TODO: Add config option for application namespace.

// Normalize project installation directory
define('ROOT', str_replace('\\','/',realpath(dirname(__FILE__).'/..')).'/');

date_default_timezone_set('America/Chicago');

// get vital config
require ROOT . 'config/vital.php';

// get implier
require ROOT . 'tools/Imply.php';
