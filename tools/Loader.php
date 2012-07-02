<?php

namespace Zule\Tools;

define('no', 0);
define('yes', 1);

// TODO: Add config option for application namespace.

// Normalize project installation directory
define('ROOT', str_replace('\\','/',realpath(dirname(__FILE__).'/..')).'/');

date_default_timezone_set('America/Chicago');

// TODO: centralize config into ini file.
// Locate and include third-party libraries
require_once ROOT . 'tools/config/libraries.php';
require_once ROOT . 'tools/Imply.php';
require_once        'Smarty.class.php';

