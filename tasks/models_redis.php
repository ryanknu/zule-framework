<?php

require_once '../tools/Loader.php';

$s = new \Smarty;
$s->display('redis_model.tpl');
